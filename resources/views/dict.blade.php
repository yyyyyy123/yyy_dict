<html>
<head>
    <title>{{ $db_name }}数据字典</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8">
    <style type="text/css">
        <!--
        .toptext {font-family: verdana; color: #000000; font-size: 20px; font-weight: 600; width:550px;  background-color:#999999;}
        .normal {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;}
        .normal_ul {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;}
        .fieldheader {white-space: nowrap; font-family: verdana; color: #000000; font-size: 16px; font-weight: 600; width:550px;  background-color:#c0c0c0;}
        .fieldcolumn {font-family: verdana; color: #000000; font-size: 16px; font-weight: 600; width:550px;  background-color:#ffffff;}
        .header {background-color: #ECE9D8;}
        .headtext {font-family: verdana; color: #000000; font-size: 20px; font-weight: 600;}
        BR.page {page-break-after: always;}
        -->

        a:link{text-decoration:none;}
        a:visited{text-decoration:none;}
        a:active{text-decoration:none;}
        a.selected{color:red}
        body {padding:20px;}
        #ul2 {margin:0; padding:0; display:grid;align-items: center; align-content: center;grid-template-columns: auto auto auto;}
        #ul2 li {display:inline;float:left;margin:5px 10px;padding:0 0;background-color:#Eee;border:1px #bbb dashed;}
        #ul2 li a{display:block;font-size:14px;color:#000;padding:10px 5px;font-weight:bolder;}
        #ul2 li:hover {background-color:#73B1E0;}
        #ul2 li:hover a {color:#FFF;}
        #div2 {clear:both;margin:20px;}
        .table2 td {padding:5px 10px;}
        .table2 tr:hover td {background-color:#73B1E0;}
        .table2 tr:hover td p{color:#FFF;}
        .table2 {border-right:1px solid #aaa; border-bottom:1px solid #aaa;}
        .table2  td{border-left:1px solid #aaa; border-top:1px solid #aaa;}
        .table2 tr:nth-child(even){background:#F4F4F4;}
        .headtext {padding:10px;}
        p.pa{color:blue;}
        .table_jiange{height:1px;margin:20px;padding:0;}
        .font2{font-size:100%;color:#222;font-family:"monospace","Consolas";}

    </style>
    <link href="/jquery/jquery-ui.css" rel="stylesheet">

    <script src="/jquery/external/jquery/jquery.js"></script>
    <script src="/jquery/jquery-ui.js"></script>

</head>
<body bgcolor="#ffffff" topmargin="0">




    @if(count($connections)>1)
        <h3>数据库连接</h3>
        <div style="display:flex;flex-flow: row wrap  ;justify-content: flex-start; align-items: flex-start ">
            @foreach($connections  as $v)
                <div style="width:24%; line-height: 40px; background-color: #f8f9fa;margin:5px;"><a
                        @if($connection== $v['connection'] )
                            class="selected"
                        @endif
                        href="/dict?connection={{  $v['connection'] }}">{{ $loop->index+1  }}.  {{  $v['connection']  }}</a></div>
            @endforeach
        </div>
    @endif

    <a name="header234">&nbsp;</a>

    <div style="clear:both;"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td width="90%" class="toptext"><p align="center">{{ $db_name }}数据字典</p></td>
            <td width="10%" style="text-align:right;" ><a id="id_mode" target="_blank"
                                href="/dictEditIndex?connection={{ $connection  }}">编辑模式 </a> </td>

        </tr>
    </table>



    <div id="div2"></div>

    <ul id="ul2">
        @foreach ($table_arr as $table)
            <li>
                <a href="#{{ $table['table_name'] }}">
                    <span class="font2">{{ $loop->index+1 }}. {{ $table['table_name'] }}</span>（{{ $table['table_comment'] }}）
                </a>
            </li>
        @endforeach
    </ul>
    <div style="clear:both;"></div>
    @if( $connection )
        <a href="#header234"><p class="normal pa">回到导航</p></a>
    @else
        <p>已检测到您尚未添加数据库连接，请点击右侧的编辑模式 - 增加连接</p>
    @endif

    <div id="div2"></div>
    <br class="page">

    @foreach ($table_arr as $table)


        <p class="table_jiange"><a name={{ $table['table_name']  }}>&nbsp;</a></p>
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td   class="headtext" align="left" valign="top">
                    {{ $loop->index+1 }}.
                    {{ $table['table_name']  }}
                    （{{ $table['table_comment']  }}）
                    （主键：{{ $table['primary']  }}）
                    （引擎：{{ $table['engine'] }}）
                </td>

            <tr>
        </table>
        <table width="100%" cellspacing="0" cellapdding="2" class="table2">
            <tr>
                <td align="center" width="3%" valign="top" class="fieldheader">&nbsp;</td>
                <td align="center" width="15%" valign="top" class="fieldheader">字段</td>
                <td align="center" width="15%" valign="top" class="fieldheader">类型</td>
                <td align="center" width="10%" valign="top" class="fieldheader">允许空</td>
                <td align="center" width="5%"  valign="top" class="fieldheader">默认值</td>
                <td align="center" width="55%" valign="top" class="fieldheader">注释</td>
            </tr>
            @foreach ($table['columns'] as $column)
                <tr>
                    <td align="left"  width="3%"><p class="normal">{{ $loop->index+1  }}</p></td>
                    <td align="left"  width="15%"><p class="normal">{{ $column['column_name']  }}</p></td>
                    <td align="left"  width="15%"><p class="normal">{{ $column['column_type']  }}</p></td>
                    <td align="left"  width="10%"><p class="normal">{{ $column['nullable']  }}</p></td>
                    <td align="left"  width="5%"><p class="normal">{{ $column['default']  }}</p></td>
                    <td align="left"  width="55%"><p class="normal">{{ $column['column_comment']  }}</p></td>
                </tr>
            @endforeach

            <tr>
                <td colspan='6'>
                    @if (count($table['indexs']) > 0)
                        <ul>
                        @foreach($table['indexs'] as $index)
                            <li>{{ $index }}</li>
                        @endforeach
                        </ul>
                    @endif
                </td>
            </tr>

        </table>
        <a href="#header234"><p class="normal pa">回到导航</p></a>
    @endforeach


    <div style="display:flex;flex-direction: column;justify-content: center;align-items: center ">
        <p style="margin:0;padding:0">Released under the MIT License.</p>
        <p style="margin:0;padding:0">Copyright © 2024-present yyy's Data Dictionary</p>

    </div>

    <script>



    </script>
</body>
</html>
