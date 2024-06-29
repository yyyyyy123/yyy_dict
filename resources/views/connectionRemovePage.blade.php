<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8"/>
    <title>{{ $db_name  }}数据字典-编辑模式-连接删除页</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <link rel="stylesheet" href="/sdk/sdk.css"/>
    <link rel="stylesheet" href="/sdk/helper.css"/>
    <link rel="stylesheet" href="/sdk/iconfont.css"/>
    <link href="/font.css"/>
    <!-- 这是默认主题所需的，如果是其他主题则不需要 -->
    <!-- 从 1.1.0 开始 sdk.css 将不支持 IE 11，如果要支持 IE11 请引用这个 css，并把前面那个删了 -->
    <!-- <link rel="stylesheet" href="sdk-ie11.css" /> -->
    <!-- 不过 amis 开发团队几乎没测试过 IE 11 下的效果，所以可能有细节功能用不了，如果发现请报 issue -->
    <style>
        html,
        body,
        .app-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div id="root" class="app-wrapper"></div>
<script src="/sdk/sdk.js"></script>
<script type="text/javascript">
    (function () {
        let amis = amisRequire('amis/embed');
        // 通过替换下面这个配置来生成不同页面
        let amisJSON =
            {
                @include('child.pagePublic')


                // 这里开始页面。
                "body": [
                    // 1、数据库连接
                        @include('child.connectionList')

                        @include('child.pageLabel',["page_label"=> $connection . "数据字典-编辑模式-连接删除页" ])

                    {
                        //3、数据表编辑页
                        "type": "form",
                        "api": "{{ route('removeConnection')  }}",
                        "title": "删除连接",
                        "mode": "inline",
                       // "confirmText": "确认要发出这个请求？",
                            "actions": [

                                {
                                    "type": "action",
                                    "level": "primary",
                                    "confirmText": "确认要删除连接{{ $connection  }}吗？",

                                    "label": "删除连接",
                                    "actionType": "ajax",
                                    "api": "{{ route('removeConnection')  }}",
                                    "redirect": "{{ route('dictEditIndex')  }}"
                                }

                            ],


                        "redirect": "{{ route('dictEditIndex')  }}",
                        "data":  @json( $connections_info,JSON_UNESCAPED_UNICODE)   ,
                        "columnCount": 3,
                        "body": [
                            {
                                "type": "input-text",
                                "name": "connection",
                                "size":"lg",
                                "label": "连接名"
                            },
                            {
                                "name": "host",
                                "type": "input-text",
                                "size":"lg",
                                "label": "主机名"
                            },
                            {
                                "name": "port",
                                "type": "input-text",
                                "size":"lg",
                                "label": "端口号"
                            },
                            {
                                "name": "db_name",
                                "type": "input-text",
                                "size":"lg",
                                "label": "　库名"
                            },
                            {
                                "name": "username",
                                "type": "input-text",
                                "size":"lg",
                                "label": "　账号"
                            },
                            {
                                "name": "password",
                                "type": "input-text",
                                "size":"lg",
                                "label": "　密码"
                            }

                        ]

                    },

                    {
                        "type":"html",
                        "className":"text-xl text-danger font-bold",
                        "html":"<p>提示：删除连接后，您之前对此库中的表的各种注释将随之一起删除</p>"
                    },
                    {
                        "type":"html",
                        "className":"text-sm text-secondary ",

                        "html":"<div>提示：删除连接时，有时提交后需要再手动刷新下页面，才能看到删除后的页面</div>"
                    },
                    {
                        "type": "divider",
                        "className":"m-10"
                    },

                    @include('child.bottomLogo')

                    //end body
                ]

                // end let amisJSON =
            };
        let amisScoped = amis.embed('#root', amisJSON);
    })();
</script>
</body>
</html>
