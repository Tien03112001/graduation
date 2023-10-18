import {AbstractCRUDService} from './crud.service.class';
import {OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {copyToClipboard, DateTimeUtil, ExcelHelper, ObjectUtil} from '../utils';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AbstractModalComponent} from './modal.component.class';
import {AppPagination, FieldForm, ModalResult} from '../common';

export abstract class AbstractCRUDComponent<T> implements OnInit, OnDestroy {

  pagination: AppPagination;
  list: T[];
  searchForm: FormGroup;
  nextPage: number;
  searchControls: FieldForm[];

  constructor(public service: AbstractCRUDService<T>, public modalService: BsModalService, public formBuilder: FormBuilder) {
    this.service.setTitle(this.getTitle());
    this.pagination = new AppPagination();
    this.nextPage = this.pagination.currentPage;
    this.list = [];
    this.searchForm = this.buildSearchForm();
    this.searchControls = this.initSearchForm();
  }

  public abstract onInit(): void;

  public abstract onDestroy(): void;

  public abstract getTitle(): string;

  public abstract getCreateModalComponent(): any;

  public abstract getEditModalComponent(): any;

  public abstract getCreateModalComponentOptions(): ModalOptions ;

  public abstract getEditModalComponentOptions(): ModalOptions ;

  public defaultModalOptions(): ModalOptions {
    return {class: 'modal-lg', backdrop: 'static', keyboard: false};
  }

  public abstract buildSearchForm(): FormGroup;

  public abstract initNewModel(): T;

  public initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Từ khóa',
        class: 'col-md-6'
      },
    ];
  }

  removeFilter() {
    this.searchForm.reset();
  }

  public load() {
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
      }, () => {
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  public onCreated(result: ModalResult<T>) {
    if (result.success) {
      let itemCreated: T = result.data;
      this.list.unshift(itemCreated);
    }
  }

  public create() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<T> = <AbstractModalComponent<T>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<T>) => {
      this.onCreated(result);
      sub.unsubscribe();
    });
  }

  public onEdited(result: ModalResult<T>, item: T, index: number) {
    if (result.success) {
      this.list[index] = ObjectUtil.combineValue(item, result.data);
    }
  }

  public edit(item: T, index: number) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<T> = <AbstractModalComponent<T>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    let sub = modal.onHidden.subscribe((result: ModalResult<T>) => {
      this.onEdited(result, item, index);
      sub.unsubscribe();
    });
  }

  public onRemoved(item: T, index: number) {
    this.list.splice(index, 1);
  }

  public remove(item: T, index: number) {
    this.service.destroy(item['id']).subscribe(() => {
      this.onRemoved(item, index);
      this.service.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());

  }

  public export(filename: string, body: any = {}) {
    this.service.export(body).subscribe(blob => {
      ExcelHelper.exportXLSXFile(blob, `${filename}_${DateTimeUtil.today('YYYY_MM_DD_X')}`);
      this.service.toastSuccessfully('Xuất file', 'Thành công');
    });
  }

  public pageChanged(event: any) {
    this.pagination.currentPage = event.page;
    this.nextPage = this.pagination.currentPage;
    this.load();
  }


  public goToPage() {
    this.pagination.currentPage = this.nextPage;
    this.load();
  }

  ngOnInit() {
    this.onInit();
  }

  ngOnDestroy() {
    this.onDestroy();
  }

  copyToClipboard(data: string) {
    copyToClipboard(data);
    this.service.toastSuccessfully('', 'Copy lưu bộ nhớ đệm');
  }

  shortenString(str: string, length: number) {
    return str.substr(0, length) + (str.length > length ? '...' : '');
  }

}
