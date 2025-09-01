<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count untuk navbar
     */
    public function getUnreadCount()
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }

        $count = NotificationService::getUnreadCount(auth()->id());
        return response()->json(['count' => $count]);
    }

    /**
     * Get notifications untuk dropdown
     */
    public function getNotifications()
    {
        if (!auth()->check()) {
            return response()->json(['notifications' => []]);
        }

        $notifications = NotificationService::getAllNotifications(auth()->id(), 10);
        
        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'is_unread' => $notification->isUnread(),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notification->type),
                    'color' => $this->getNotificationColor($notification->type)
                ];
            })
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        if (!auth()->check()) {
            return response()->json(['success' => false], 401);
        }

        NotificationService::markAllAsRead(auth()->id());
        return response()->json(['success' => true]);
    }

    /**
     * Get icon untuk jenis notifikasi
     */
    private function getNotificationIcon($type)
    {
        $icons = [
            'surat_diproses' => 'fas fa-clock',
            'surat_selesai' => 'fas fa-check-circle',
            'surat_approved' => 'fas fa-check-double',
            'surat_ditolak' => 'fas fa-times-circle',
            'kematian_verifikasi' => 'fas fa-cross',
            'kematian_approved' => 'fas fa-check-circle',
            'kematian_ditolak' => 'fas fa-times-circle'
        ];

        return $icons[$type] ?? 'fas fa-bell';
    }

    /**
     * Get color untuk jenis notifikasi
     */
    private function getNotificationColor($type)
    {
        $colors = [
            'surat_diproses' => 'text-blue-600',
            'surat_selesai' => 'text-green-600',
            'surat_approved' => 'text-emerald-600',
            'surat_ditolak' => 'text-red-600',
            'kematian_verifikasi' => 'text-red-700',
            'kematian_approved' => 'text-green-600',
            'kematian_ditolak' => 'text-red-600'
        ];

        return $colors[$type] ?? 'text-gray-600';
    }
}
