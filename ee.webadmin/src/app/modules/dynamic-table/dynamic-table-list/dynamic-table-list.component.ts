import {Component} from '@angular/core';
import {AbstractCRUDComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';
import {AbstractModalComponent} from '../../../core/crud';
import {DynamicTableMeta} from '../dynamic-table.meta';
import {DynamicTableService} from '../dynamic-table.service';
import {DynamicTableCreateComponent} from '../dynamic-table-create/dynamic-table-create.component';
import {DynamicTableEditComponent} from '../dynamic-table-edit/dynamic-table-edit.component';
import {DynamicTableColumnMeta} from '../../dynamic-table-column/dynamic-table-column.meta';
import {DynamicTableColumnCreateComponent} from '../../dynamic-table-column/dynamic-table-column-create/dynamic-table-column-create.component';
import {DynamicTableRowCreateComponent} from '../../dynamic-table-row/dynamic-table-row-create/dynamic-table-row-create.component';
import {DynamicTableRowService} from '../../dynamic-table-row/dynamic-table-row.service';
import {DynamicTableColumnService} from '../../dynamic-table-column/dynamic-table-column.service';
import {DynamicTableColumnEditComponent} from '../../dynamic-table-column/dynamic-table-column-edit/dynamic-table-column-edit.component';
import {DynamicTableRowMeta} from '../../dynamic-table-row/dynamic-table-row.meta';
import {DynamicTableCellMeta} from '../../dynamic-table-cell/dynamic-table-cell.meta';
import {DynamicTableRowEditComponent} from '../../dynamic-table-row/dynamic-table-row-edit/dynamic-table-row-edit.component';

@Component({
  selector: 'app-dynamic-table',
  templateUrl: './dynamic-table-list.component.html',
  styleUrls: ['./dynamic-table-list.component.css'],
  providers: [DynamicTableService, DynamicTableRowService, DynamicTableColumnService]
})
export class DynamicTableListComponent extends AbstractCRUDComponent<DynamicTableMeta> {
  tableOneColumnData: any = [];
  panelOpenState: boolean = false;
  selectedIndex: number;

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý thông tin công ty';
  }

  getCreateModalComponent(): any {

    return DynamicTableCreateComponent;
  }

  getEditModalComponent(): any {
    return DynamicTableEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): DynamicTableMeta {
    return new DynamicTableMeta();
  }

  public createTable(item: DynamicTableMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableCreateComponent, config);
    const modal: AbstractModalComponent<DynamicTableMeta> = <AbstractModalComponent<DynamicTableMeta>>modalRef.content;
    const dynamicTable = new DynamicTableMeta();
    dynamicTable.id = item.id;
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        const itemCreated: DynamicTableColumnMeta = result.data.data;
        this.list[index].columns.unshift(itemCreated);
      }
      sub.unsubscribe();
    });
  }

  check(index) {
    this.selectedIndex = index;
  }

  public createColumn(item: DynamicTableMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableColumnCreateComponent, config);
    const modal: AbstractModalComponent<DynamicTableColumnMeta> = <AbstractModalComponent<DynamicTableColumnMeta>>modalRef.content;
    const dynamicTable = new DynamicTableColumnMeta();
    dynamicTable.table_id = item.id;
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        item.columns = result.data.columns;
        item.rows = result.data.rows;
        item.cells = result.data.cells;
      }
      sub.unsubscribe();
    });
  }

  public deleteColumn(item: DynamicTableMeta, index: number) {
    if (this.itemSelect.length <= 0) {
      this.dynamicTableColumnService.toastError('Bạn chưa chọn cột cần xóa!!!');
    } else {
      this.dynamicTableService.destroyColumn(item.id, this.itemSelect).subscribe((result: any) => {
        item.columns = result.data.columns;
        item.rows = result.data.rows;
        item.cells = result.data.cells;
        this.itemSelect = [];
        this.dynamicTableService.toastSuccess('Thành công', 'Xóa cột thành công');
      });
    }
  }

  public deleteRow(item: any, cells: any, i: number, j: number) {
    this.dynamicTableRowService.destroy(item['id']).subscribe(() => {
      this.list[i].rows.splice(j, 1);
      this.dynamicTableRowService.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());
  }

  public createRow(item: DynamicTableMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableRowCreateComponent, config);
    const modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    const dynamicTable = item;
    modal.setModel(item);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        item.columns = result.data.columns;
        item.rows = result.data.rows;
        item.cells = result.data.cells;
      }
      sub.unsubscribe();
    });
  }

  itemSelect = [];

  getItemSelect(ev) {
    if (ev.target.checked == true) {
      this.itemSelect.push(ev.target.value);
    } else {
      for (let i = 0; i < this.itemSelect.length; i++) {
        if (this.itemSelect[i] == ev.target.value) {
          this.itemSelect.splice(i, 1);
        }
      }
    }
  }

  editColumn(table: DynamicTableMeta, column: DynamicTableColumnMeta, index: number, j: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponent());
    const modalRef = this.modalService.show(DynamicTableColumnEditComponent, config);
    const modal: AbstractModalComponent<DynamicTableColumnMeta> = <AbstractModalComponent<DynamicTableColumnMeta>>modalRef.content;
    const dynamicTable = new DynamicTableColumnMeta();
    dynamicTable.name = column.name;
    dynamicTable.id = column.id;
    dynamicTable.table_id = column.table_id;
    dynamicTable.description = column.description;
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        const itemCreated: DynamicTableColumnMeta = result.data;
        this.list[index].columns[j] = itemCreated;
      }
      sub.unsubscribe();
    });
  }

  editRow(columns: DynamicTableColumnMeta[], row: DynamicTableRowMeta, cells: DynamicTableCellMeta[], index: number, j: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableRowEditComponent, config);
    const modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    for (let i = 0; i < cells.length; i++) {
      for (let j = 0; j < columns.length; j++) {
        if (cells[i].row_id == row.id && cells[i].column_id == columns[j].id) {
          columns[j]['cell'] = cells[i];
        }
      }
    }
    const dynamicTable = {
      id: row.id,
      columns: columns,
      table_id: row.table_id
    };
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        const itemCreated: any = result.data;
        this.list[index].cells = itemCreated.cells;
      }
      sub.unsubscribe();
    });
  }

  constructor(
    service: DynamicTableService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private dynamicTableColumnService: DynamicTableColumnService,
    private dynamicTableService: DynamicTableService,
    private dynamicTableRowService: DynamicTableRowService
  ) {
    super(service, modal, builder);
  }

}
