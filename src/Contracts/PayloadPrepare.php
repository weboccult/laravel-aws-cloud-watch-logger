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
        $payload = [
            "project"   => $this->options['project_name'],
        ];
        if (isset($this->modelConfig['fields']) && !empty($this->modelConfig['fields']) && collect($this->modelConfig['fields'])->count() > 0) {
            foreach ($this->modelConfig['fields'] as $key => $field) {
                $payload[$key] = $this->model->$field;
            }
        }
        $payloadData = [
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
        return array_merge($payload,$payloadData);
    }
}
