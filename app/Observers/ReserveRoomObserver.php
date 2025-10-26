<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ReserveRoom;
use Filament\Notifications\Notification;

class ReserveRoomObserver
{
    public function created(ReserveRoom $reserveRoom): void
    {
        // Notify the person who submitted the reservation
        Notification::make()
            ->title('Reservation Submitted')
            ->body("Your reservation request has been successfully submitted and is pending review.")
            ->icon('heroicon-o-clipboard-document-check')
            ->success()
            ->sendToDatabase(auth()->user(), isEventDispatched: true);

        // Notify all admins and superadmins
        foreach (User::all() as $user) {
            if ($user->hasAnyRole(['admin', 'super_admin'])) {
                Notification::make()
                    ->title('New Reservation Submitted')
                    ->body("A reservation has been submitted by {$reserveRoom->reserved_by}.")
                    ->icon('heroicon-o-bell')
                    ->success()
                    ->sendToDatabase($user, isEventDispatched: true);
            }
        }
    }

    public function updated(ReserveRoom $reserveRoom): void
    {
        if ($reserveRoom->wasChanged('status')) {
            $user = $reserveRoom->user;

            if ($user) {
                $status = strtolower($reserveRoom->status);
            
                $icon = match ($status) {
                    'approved' => 'heroicon-s-check-circle',
                    'rejected' => 'heroicon-s-x-circle',
                    default => 'heroicon-s-clock',
                };
            
                $iconColor = match ($status) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                };
            
                // Notify the incubatee (the reservation owner)
                Notification::make()
                    ->title('Reservation ' . ucfirst($status))
                    ->body("Your reservation has been " . ucfirst($status) . " by the administrator.")
                    ->icon($icon)
                    ->iconColor($iconColor)
                    ->sendToDatabase($user, isEventDispatched: true);
            
                // Notify the admin/superadmin who performed the update
                $admin = auth()->user();
                if ($admin && $admin->hasAnyRole(['admin', 'super_admin'])) {
                    Notification::make()
                        ->title('Reservation Updated')
                        ->body("You have successfully {$status} a reservation for {$user->name}.")
                        ->icon($icon)
                        ->iconColor($iconColor)
                        ->sendToDatabase($admin, isEventDispatched: true);
                }
            }            
        }
    }
}
