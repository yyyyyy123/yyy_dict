{
  "type": "panel",
  "title": "数据库连接",
  "data":{
    "connection":"{{ $connection  }}"
  },
  "body":[
    {
      "type": "flex",
      "justify": "flex-start",
      "style": {
        "flexWrap": "wrap"
      },
      "items": {!! $connections_data  !!}
    },
    {
      "type": "flex",
      "justify": "flex-end",
      "style": {
        "flexWrap": "wrap"
      },
      "items": [
        {
            "type":"button",
            "label":"编辑模式导航页",
            "className":"m-2",
            "actionType": "link",
            "link": "/dictEditIndex"
        },
        {
          "type":"button",
          "label":"连接添加页",
          "className":"m-2",
          "actionType": "link",
          "link": "/connectionAddPage"
        },

        {
            "type":"button",
            "label":"连接删除页",
            "visibleOn": "${connection}",
            "className":"m-2  ",
            "actionType": "link",
            "link": "/connectionRemovePage?connection={{ $connection  }}"
        },

        {
            "type":"button",
            "label":"帮助页面",
            "className":"m-2  ",
            "actionType": "link",
            "link": "/help"
        }
      ]
    }
  ]
},
