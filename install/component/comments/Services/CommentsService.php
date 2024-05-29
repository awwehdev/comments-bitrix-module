<?php

namespace Bitrix\Components\Comments\Comments\Services;

use Bitrix\Components\Comments\Comments\Traits\NewInstance;

class CommentsService
{
    use NewInstance;

    public function asTree($nodes)
    {
        $data = [];
        foreach (($nodes) as $node) {
            $data[$node['ID']] = $node;
        }

        $data = array_reverse($data, true);
        $count = 0;
        while (true) {
            $node = current($data);

            if (!$node) {
                break;
            }

            if (!empty($node['reply_id'])) {
                if (!is_array($data[$node['reply_id']]['children'])) {
                    $data[$node['reply_id']]['children'] = [];
                }
                $data[$node['reply_id']]['children'] = array_merge([$node], $data[$node['reply_id']]['children']);
                $data[$node['id']] = null;
            } elseif (empty($data[$node['id']]['children'])) {
                $data[$node['id']]['children'] = [];
            }

            next($data);

            if ($count == count($nodes)) {
                break;
            }

            $count++;
            if ($count >= PHP_INT_MAX) {
                break;
            }
        }

        $data = array_filter($data);

        return $data;
    }
}