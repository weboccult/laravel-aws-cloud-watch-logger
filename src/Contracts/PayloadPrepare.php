<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Contracts;

/**
 * helper method for Driver class
 */
trait PayloadPrepare
{
    /**
     * @return array
     */
    public function preparePayload(): array
    {
        return [
            "project"   => $this->options['project_name'],
            "storeId"   => $this->store->id,
            "storeSlug" => $this->store->store_slug,
            "module"    => $this->module,
            "operation" => $this->operation,
            "date"      => \Carbon\Carbon::now()->format('d-m-Y'),
            "time"      => \Carbon\Carbon::now()->format('H:i:s'),
            "payload"   => [
                "data"   => $this->data,
                "before" => [],
                "after"  => [],
                "extra"  => [
                    'ipAddress'  => request()->getClientIp(),
                    'browser'    => request()->header('User-Agent'),
                    //                'origin'=>request()->headers->get('origin'),
                    'host'       => request()->server('HTTP_HOST'),
                    'currentUrl' => request()->getRequestUri(),
                    'protocal'   => request()->server('SERVER_PROTOCOL'),
                ]
            ]
        ];
    }
}
