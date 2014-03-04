<?php
namespace App\Models;

use Suricate\Suricate;

class Notification
{
    public static $types = array(
        'success',
        'error'
    );

    public static function read()
    {
        $output = '';
        // Get notification
        $notifications = Fwk::Session()->read('notifications');

        // Build html
        if (is_array($notifications)) {
            foreach ($notifications as $notificationType => $notificationMessages) {
                foreach ($notificationMessages as $currentMessage) {
                    if ($notificationType == 'success') {
                        $icon = 'icon-ok-sign';
                    } else {
                        $icon = 'icon-warning-sign';
                    }
                    $output .= '<div class="notification ' . $notificationType . '"><i class="' . $icon . '"></i> ' . implode('<br/>', (array) $currentMessage) . '</div>';;
                }
            }
        }

        // Erase (consume)
        Fwk::Session()->destroy('notifications');

        return $output;
    }

    public static function write($type, $message)
    {
        if (in_array($type, static::$types)) {
            $currentSessionData = Fwk::Session()->read('notifications');

            if (isset($currentSessionData[$type]) && is_array($currentSessionData[$type])) {
                $newData = array_merge($currentSessionData[$type], (array) $message);
            } else {

                $newData = (array) $message;
            }

            $currentSessionData[$type] = $newData;
            Fwk::Session()->write('notifications', $currentSessionData);
        }
    }
}
