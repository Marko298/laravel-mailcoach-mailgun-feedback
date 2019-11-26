<?php

namespace Spatie\MailcoachMailgunFeedback;

use Spatie\MailcoachMailgunFeedback\MailgunEvents\ClickEvent;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\ComplaintEvent;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\MailgunEvent;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\OpenEvent;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\OtherEvent;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\PermanentBounceEvent;

class MailgunEventFactory
{
    protected static $mailgunEvents = [
        ClickEvent::class,
        ComplaintEvent::class,
        OpenEvent::class,
        PermanentBounceEvent::class,
    ];

    public static function createForPayload(array $payload): MailgunEvent
    {
        $mailgunEvent = collect(static::$mailgunEvents)
            ->map(function (string $mailgunEventClass) use ($payload) {
                return new $mailgunEventClass($payload);
            })
            ->first(function (MailgunEvent $mailgunEvent) use ($payload) {
                return $mailgunEvent->canHandlePayload();
            });

        return $mailgunEvent ?? new OtherEvent($payload);
    }
}