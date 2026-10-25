<?php

namespace yii\swiftcurrency\enum;

enum Status: string
{
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
    case PENDING = 'PENDING';
}
