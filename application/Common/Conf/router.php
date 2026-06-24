<?php
return array(
    'DEFAULT_MODULE' => 'platform',
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        'api/getkeyword' => array(
            'api/index/Getkeywords',
            'types=ssc'
        ),
        'api/getarticle' => array(
            'api/index/getarticle'
        ),
        'api/getarticletitle' => array(
            'api/index/getarticletitle',
            'limit=10'
        ),
        'api/templates' => array(
            'api/index/gettemplates',
            'types=1'
        ),
        'api/test' => array(
            'api/index/test'
        ),
        'api/t1' => array(
            'api/index/t1'
        ),
        'api/t2' => array(
            'api/index/unitTest'
        ),
        'api/json' => array(
            'api/index/put_data'
        ),
        'api/chenk' => array(
            'api/index/chenk'
        ),
        'api/slink' => array(
            'api/index/slink'
        ),
        'api/link' => array(
            'api/index/slink_href'
        ),
        'api/global' => array(
            'api/index/slink_global'
        ),
		'api/href' => array(
            'api/index/slink_dt'
        ),
        'api/token' => array(
            'api/index/get_ask'
        ),
        'api/push' => array(
            'api/index/baidu_push'
        ),
        'api/status' => array(
            'api/index/get_siteid'
        ),
        'api/types' => array(
            'api/index/get_types'
        ),
        'api/zz' => array(
            'api/index/zz'
        ),
        'api/monitor' => array(
            'api/monitor/get'
        ),
        'api/blank' => array(
            'api/index/new_slink'
        ),
        'api/background'  => array(
            'api/index/back'
        ),
        '/^i\/(\w{6})$/' => array(
            'api/link/getLink?alias=:1'
        )
    )
);