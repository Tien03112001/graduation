import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination, ModalResult, PaginationOutput} from '../../../core/common';
import {DynamicTableMeta} from '../dynamic-table.meta';
import {DynamicTableService} from '../dynamic-table.service';
import {DynamicTableCreateComponent} from '../dynamic-table-create/dynamic-table-create.component';
import {DynamicTableEditComponent} from '../dynamic-table-edit/dynamic-table-edit.component';
import {DynamicTableColumnMeta} from '../../dynamic-table-column/dynamic-table-column.meta';
import {DynamicTableRowCreateComponent} from '../../dynamic-table-row/dynamic-table-row-create/dynamic-table-row-create.component';
import {DynamicTableRowService} from '../../dynamic-table-row/dynamic-table-row.service';
import {DynamicTableColumnService} from '../../dynamic-table-column/dynamic-table-column.service';
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
  selectedIndex: number;

  listData: any[];
  listPaginations: AppPagination[];

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý dữ liệu công ty';
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

  check(index) {
    this.selectedIndex = index;
  }

  createRow(item: DynamicTableMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(DynamicTableRowCreateComponent, config);
    const modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    modal.setModel(item);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.loadTable(item);
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
      table_id: row.table_id,
      row_id: row.id,
    };
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      console.log(result);
      if (result.success) {
        const itemCreated: any = result.data;
        this.list[index].cells = itemCreated.cells;
      }
      sub.unsubscribe();
    });
  }

  deleteRow(cells: any, j: number) {
    this.dynamicTableRowService.destroy(cells['id']).subscribe(() => {
      this.list[j].rows.splice(j, 1);
      this.dynamicTableRowService.toastSuccessfullyDeleted();
      this.load()
    }, () => this.service.toastFailedDeleted());
  }

  tableSetPagination(tableId: number, output: PaginationOutput<any>) {
    this.listPaginations[tableId] = new AppPagination();
    this.listPaginations[tableId].set(output);
  }

  loadTable(item: DynamicTableMeta) {
    this.loadTableDataByTableId(item.id);
  }

  loadTableDataByTableId(tableId: number) {
    const param = {
      table_id: tableId,
      limit: this.listPaginations[tableId].itemsPerPage,
      page: this.listPaginations[tableId].currentPage,
      orderBy: 'id:desc'
    };
    this.dynamicTableRowService.loadByParams(param).subscribe((res: any) => {
        this.listData[tableId] = res.data;
        this.tableSetPagination(tableId, res);
      }, () => {
        this.listData[tableId] = [];
        this.listPaginations[tableId] = new AppPagination();
      }
    );
  }

  public tablePageChanged(event: any, tableId: number) {
    this.listPaginations[tableId].currentPage = event.page;
    this.loadTableDataByTableId(tableId);
  }

  constructor(
    service: DynamicTableService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private dynamicTableColumnService: DynamicTableColumnService,
    private dynamicTableService: DynamicTableService,
    private dynamicTableRowService: DynamicTableRowService,
  ) {
    super(service, modal, builder);
    this.listData = [];
    this.listPaginations = [];
  }

  public load() {
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
        this.listData = [];
        this.listPaginations = [];
        this.list.forEach(position => {
          this.listData[position.id] = [];
          this.listPaginations[position.id] = new AppPagination();
        });
      }, () => {
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
        this.listData = [];
        this.listPaginations = [];
      }
    );
  }

}
