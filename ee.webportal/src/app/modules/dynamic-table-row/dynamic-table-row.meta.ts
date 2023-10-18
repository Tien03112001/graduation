import {DynamicTableCellMeta} from '../dynamic-table-cell/dynamic-table-cell.meta';

export class DynamicTableRowMeta {
  id: number;
  table_id: number;
  description: string;
  column_id: number;
  row_value: string;
  cells: DynamicTableCellMeta[];

}
