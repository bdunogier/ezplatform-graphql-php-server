<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\Notification\CreateStruct;
use App\eZ\Platform\API\Repository\Values\Notification\Notification;
use App\eZ\Platform\API\Repository\Values\Notification\NotificationList;

/**
 * Service to manager user notifications. It works in the context of a current User (obtained from
 * the PermissionResolver).
 */
interface NotificationService
{
    /**
     * Get currently logged user notifications.
     *
     * @param int $offset the start offset for paging
     * @param int $limit  the number of notifications returned
     *
     * @return \App\eZ\Platform\API\Repository\Values\Notification\NotificationList
     */
    public function loadNotifications(int $offset, int $limit): NotificationList;

    /**
     * Load single notification (by ID).
     *
     * @param int $notificationId Notification ID
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException
     *
     * @return \App\eZ\Platform\API\Repository\Values\Notification\Notification
     */
    public function getNotification(int $notificationId): Notification;

    /**
     * Mark notification as read so it no longer bother the user.
     *
     * @param \eZ\Platform\API\Repository\Values\Notification\Notification $notification
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException
     */
    public function markNotificationAsRead(Notification $notification): void;

    /**
     * Get count of unread users notifications.
     *
     * @return int
     */
    public function getPendingNotificationCount(): int;

    /**
     * Get count of total users notifications.
     *
     * @return int
     */
    public function getNotificationCount(): int;

    /**
     * Creates a new notification.
     *
     * @param \eZ\Platform\API\Repository\Values\Notification\CreateStruct $createStruct
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException
     *
     * @return \App\eZ\Platform\API\Repository\Values\Notification\Notification
     */
    public function createNotification(CreateStruct $createStruct): Notification;

    /**
     * Deletes a notification.
     *
     * @param \eZ\Platform\API\Repository\Values\Notification\Notification $notification
     */
    public function deleteNotification(Notification $notification): void;
}
