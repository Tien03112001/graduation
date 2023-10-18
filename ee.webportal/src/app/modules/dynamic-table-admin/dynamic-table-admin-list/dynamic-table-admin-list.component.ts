import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';
import {DynamicTableAdminMeta} from '../dynamic-table-admin.meta';
import {DynamicTableAdminService} from '../dynamic-table-admin.service';
import {DynamicTableAdminCreateComponent} from '../dynamic-table-admin-create/dynamic-table-admin-create.component';
import {DynamicTableAdminEditComponent} from '../dynamic-table-admin-edit/dynamic-table-admin-edit.component';
import {DynamicTableAdminColumnMeta} from '../../dynamic-table-admin-column/dynamic-table-admin-column.meta';
import {DynamicTableAdminColumnCreateComponent} from '../../dynamic-table-admin-column/dynamic-table-admin-column-create/dynamic-table-admin-column-create.component';
import {DynamicTableAdminRowCreateComponent} from '../../dynamic-table-admin-row/dynamic-table-admin-row-create/dynamic-table-admin-row-create.component';
import {DynamicTableAdminRowService} from '../../dynamic-table-admin-row/dynamic-table-admin-row.service';
import {DynamicTableAdminColumnService} from '../../dynamic-table-admin-column/dynamic-table-admin-column.service';
import {DynamicTableAdminColumnEditComponent} from '../../dynamic-table-admin-column/dynamic-table-admin-column-edit/dynamic-table-admin-column-edit.component';
import {DynamicTableAdminRowMeta} from '../../dynamic-table-admin-row/dynamic-table-admin-row.meta';
import {DynamicTableAdminCellMeta} from '../../dynamic-table-admin-cell/dynamic-table-admin-cell.meta';
import {DynamicTableAdminRowEditComponent} from '../../dynamic-table-admin-row/dynamic-table-admin-row-edit/dynamic-table-admin-row-edit.component';
import {DynamicTableColumnMeta} from '../../dynamic-table-column/dynamic-table-column.meta';

@Component({
  selector: 'app-dynamic-table-admin',
  templateUrl: './dynamic-table-admin-list.component.html',
  styleUrls: ['./dynamic-table-admin-list.component.css'],
  providers: [DynamicTableAdminService, DynamicTableAdminRowService, DynamicTableAdminColumnService]
})
export class DynamicTableAdminListComponent extends AbstractCRUDComponent<DynamicTableAdminMeta> {
  tableOneColumnData: any = [];
  panelOpenState: boolean = false;
  selectedIndex: number;

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản trị thông tin công ty';
  }

  getCreateModalComponent(): any {

    return DynamicTableAdminCreateComponent;
  }

  getEditModalComponent(): any {
    return DynamicTableAdminEditComponent;
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

  initNewModel(): DynamicTableAdminMeta {
    return new DynamicTableAdminMeta();
  }

  public createTable(item: DynamicTableAdminMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableAdminCreateComponent, config);
    const modal: AbstractModalComponent<DynamicTableAdminMeta> = <AbstractModalComponent<DynamicTableAdminMeta>>modalRef.content;
    const dynamicTable = new DynamicTableAdminMeta();
    dynamicTable.id = item.id;
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        const itemCreated: DynamicTableAdminColumnMeta = result.data.data;
        this.list[index].columns.unshift(itemCreated);
      }
      sub.unsubscribe();
    });
  }

  check(index) {
    this.selectedIndex = index;
  }

  public createColumn(item: DynamicTableAdminMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableAdminColumnCreateComponent, config);
    const modal: AbstractModalComponent<DynamicTableAdminColumnMeta> = <AbstractModalComponent<DynamicTableAdminColumnMeta>>modalRef.content;
    const dynamicTable = new DynamicTableAdminColumnMeta();
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

  public deleteColumn(item: DynamicTableAdminMeta, index: number) {
    this.dynamicTableColumnService.destroy(index).subscribe(() => {
      this.dynamicTableColumnService.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());
  }

  public deleteRow(item: any, cells: any, i: number, j: number) {
    this.dynamicTableRowService.destroy(item['id']).subscribe(() => {
      this.list[i].rows.splice(j, 1);
      this.dynamicTableRowService.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());
  }

  public createRow(item: DynamicTableAdminMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableAdminRowCreateComponent, config);
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

  editColumn(table: DynamicTableAdminMeta, column: DynamicTableAdminColumnMeta, index: number, j: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponent());
    const modalRef = this.modalService.show(DynamicTableAdminColumnEditComponent, config);
    const modal: AbstractModalComponent<DynamicTableAdminColumnMeta> = <AbstractModalComponent<DynamicTableAdminColumnMeta>>modalRef.content;
    const dynamicTable = new DynamicTableAdminColumnMeta();
    dynamicTable.name = column.name;
    dynamicTable.id = column.id;
    dynamicTable.table_id = column.table_id;
    dynamicTable.description = column.description;
    dynamicTable.type = column.type;
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        const itemCreated: DynamicTableAdminColumnMeta = result.data;
        this.list[index].columns[j] = itemCreated;
      }
      sub.unsubscribe();
    });
  }

  editRow(columns: DynamicTableColumnMeta[], row: DynamicTableAdminRowMeta, cells: DynamicTableAdminCellMeta[], index: number, j: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponent());
    const modalRef = this.modalService.show(DynamicTableAdminRowEditComponent, config);
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
    service: DynamicTableAdminService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private dynamicTableColumnService: DynamicTableAdminColumnService,
    private dynamicTableService: DynamicTableAdminService,
    private dynamicTableRowService: DynamicTableAdminRowService
  ) {
    super(service, modal, builder);
  }

}
