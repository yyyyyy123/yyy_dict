{
  "type": "flex",
  "direction": "column",
  "alignItems":"center",
  "className": "text-2xl  ",
  "items": [
    {
      "type": "tpl",
      "tpl": "{{ $page_label  }}"
    },
    {
      "style":{ "alignSelf": "flex-end" },
      "className":" text-lg",
      "alignSelf":"flex-end",
      "type": "link",
      "href": "/dict?connection={{  $connection }}",
      "blank":true,
      "body":"普通模式"
    }
  ]
},
