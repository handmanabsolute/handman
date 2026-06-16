<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Tugas;
use App\Models\GrupKerja;
use App\Models\Notification;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('components.topbar', function ($view) {
            $user = Auth::user();
            $unreadCount = 0;
            $notifications = collect();

            if ($user) {
                $departemenId = $user->departemen_id;
                $userId = $user->id;

                if ($user->nama_role === 'manager' && $departemenId) {
                    $nearingDeadlineTasks = Tugas::where('departemen_id', $departemenId)
                        ->where('status_tugas', '!=', 'Selesai')
                        ->whereBetween('deadline_tugas', [now(), now()->addHours(24)])
                        ->get();

                    foreach ($nearingDeadlineTasks as $task) {
                        $exists = Notification::where('user_id', $userId)
                            ->where('type', 'deadline_mendekati')
                            ->where('related_id', $task->id)
                            ->exists();

                        if (!$exists) {
                            Notification::create([
                                'user_id'    => $userId,
                                'title'      => 'Tugas Mendekati Deadline',
                                'message'    => 'Tugas "' . $task->nama_tugas . '" mendekati batas waktu pengerjaan (' . Carbon::parse($task->deadline_tugas)->diffForHumans() . ').',
                                'type'       => 'deadline_mendekati',
                                'related_id' => $task->id,
                            ]);
                        }
                    }
                } elseif ($user->nama_role === 'staff' && $departemenId) {
                    $myGrupIds = GrupKerja::whereHas('anggota', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->pluck('id');

                    $nearingDeadlineTasks = Tugas::where('departemen_id', $departemenId)
                        ->where('status_tugas', '!=', 'Selesai')
                        ->whereBetween('deadline_tugas', [now(), now()->addHours(24)])
                        ->where(function ($query) use ($userId, $myGrupIds) {
                            $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                                $q->where('user_id', $userId)
                                  ->orWhereIn('grup_kerja_id', $myGrupIds);
                            })
                            ->orWhereDoesntHave('detailTugas');
                        })
                        ->get();

                    foreach ($nearingDeadlineTasks as $task) {
                        $exists = Notification::where('user_id', $userId)
                            ->where('type', 'deadline_mendekati')
                            ->where('related_id', $task->id)
                            ->exists();

                        if (!$exists) {
                            Notification::create([
                                'user_id'    => $userId,
                                'title'      => 'Tugas Mendekati Deadline',
                                'message'    => 'Tugas "' . $task->nama_tugas . '" mendekati batas waktu pengerjaan (' . Carbon::parse($task->deadline_tugas)->diffForHumans() . ').',
                                'type'       => 'deadline_mendekati',
                                'related_id' => $task->id,
                            ]);
                        }
                    }
                }

                $notifications = Notification::where('user_id', $userId)
                    ->latest()
                    ->take(10)
                    ->get();

                $unreadCount = Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count();
            }

            $view->with(compact('unreadCount', 'notifications'));
        });
    }
}
