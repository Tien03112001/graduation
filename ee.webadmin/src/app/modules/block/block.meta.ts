import {WidgetMeta} from '../widget/widget.meta';

export class BlockMeta {
  id: number;
  page_id: number;
  widget_id: number;
  widget_name: string;
  order: number;
  widget_html: string;

  widget: WidgetMeta;
}
