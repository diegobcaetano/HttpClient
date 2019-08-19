<?php

namespace MadeiraMadeiraBr\HttpClient\Tests\Stub;

use MadeiraMadeiraBr\Event\ObserverInterface;
use SplSubject;

class Observer implements ObserverInterface
{
    public static $eventResult = null;

    /**
     * Get execution priority
     *
     * @return integer
     */
    public function getPriority(): int
    {
        return 0;
    }

    /**
     * Receive update from subject
     * @link https://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject)
    {
        self::$eventResult = $subject->getEvent();
    }
}