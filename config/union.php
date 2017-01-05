<?php

return [
    'sdk'=>[
        'sts'=>[
            'OSS_ACCESS_ID'=>'--------',
            'OSS_ACCESS_KEY'=>'--------',
            'roleArn'=>'acs:ram::--------:role/--------',
            "policy"=><<<POLICY
{
    "Version": "1",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": "oss:PutObject",
            "Resource": "acs:oss:*:*:--------"
        }
    ]
}
POLICY
        ]
    ]

];
