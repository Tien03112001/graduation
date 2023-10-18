import {DynamicTableColumnMeta} from '../dynamic-table-column/dynamic-table-column.meta';
import {DynamicTableRowMeta} from '../dynamic-table-row/dynamic-table-row.meta';
import {DynamicTableCellMeta} from '../dynamic-table-cell/dynamic-table-cell.meta';

export class DynamicTableAdminMeta {
  id: number;
  name: string;
  description: string;
  type: string;
  columns: DynamicTableColumnMeta[];
  rows: DynamicTableRowMeta[];
  cells: DynamicTableCellMeta[];
}
