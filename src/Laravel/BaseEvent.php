<?php

namespace N30\Omnisend\Laravel;

use N30\Omnisend\EventInterface;
use N30\Omnisend\Laravel\Jobs\TriggerEvent;
use N30\Omnisend\Omnisend;

abstract class BaseEvent implements EventInterface
{
    /**
     * @param string $email
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fire(string $email): void
    {
        if (config('omnisend.enabled')) {
            /** @var Omnisend $omnisend */
            $omnisend = app(Omnisend::class);
            $omnisend->triggerEvent($this, $email);
        }
    }

    /**
     * @param string $email
     * @param string|null $queue
     */
    public function enqueue(string $email, ?string $queue = null): void
    {
        if (config('omnisend.enabled')) {
            $queue = $queue ?? config('omnisend.queue');

            TriggerEvent::dispatch($this, $email)->onQueue($queue);
        }
    }
}
