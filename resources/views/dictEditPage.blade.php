<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8"/>
    <title>{{ $db_name  }}数据字典-编辑模式-编辑页</title>
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

                    // 为了页面跳转到开头。
                    {
                        "type": "html",
                        "html": "<a name='header234'>&nbsp;</a>"
                    },

                    // 2、大标题，某某数据库。
                    @include('child.pageLabel',["page_label"=> $connection . "数据字典-编辑模式-编辑页" ])

                    //3、数据表导航
                    {
                        "type": "panel",
                        "title": "数据表导航(可点击)",
                        "body":
                            {
                                "type": "flex",
                                "justify": "flex-start",
                                "style": {
                                    "flexWrap": "wrap"
                                },
                                "items": {!! $table_nav_data  !!}
                            }
                    },

                    {
                        "type": "html",
                        "html": "<a name='header2345'>&nbsp;</a>"
                    },

                    //3、数据表编辑页
                    {
                        "type": "panel",
                        "title": "数据表编辑",
                        "body":  {!! $table_data  !!}

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
