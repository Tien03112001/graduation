export class ChannelMeta {
  id:number;
  name:string;
  value:string;
  priority:number;
}

export class ChannelMetaConstant {
  public static LIST: any[] = [
    {
      text: 'Facebook',
      value: 'Facebook'
    },
    {
      text: 'Livestream',
      value: 'Livestream'
    },
    {
      text: 'Website',
      value: 'Website'
    },
    {
      text: 'Zalo',
      value: 'Zalo'
    },
    {
      text: 'Telesale',
      value: 'Telesale'
    },
    {
      text: 'Khác',
      value: 'Khác'
    }
  ];
}

