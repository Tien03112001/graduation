import {AbstractCRUDService} from './crud.service.class';
import {EventEmitter, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {copyToClipboard, ObjectUtil} from '../utils';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AbstractModalComponent} from './modal.component.class';
import {AppPagination, FieldForm, ModalResult} from '../common';

export abstract class AbstractCRUDModalComponent<T> implements OnInit, OnDestroy {

  pagination: AppPagination;
  list: T[];
  searchForm: FormGroup;
  nextPage: number;
  relatedModel: any;

  searchControls: FieldForm[];

  newEndPointAPI: string;

  onHidden: EventEmitter<any> = new EventEmitter<any>();


  protected constructor(public service: AbstractCRUDService<T>, public modal: BsModalRef, public modalService: BsModalService, public formBuilder: FormBuilder) {
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

  public defaultModalOptions(): ModalOptions {
    return {class: 'modal-lg', backdrop: 'static', keyboard: false};
  }

  public abstract getCreateModalComponentOptions(): ModalOptions;

  public abstract getEditModalComponentOptions(): ModalOptions;

  public abstract buildSearchForm(): FormGroup;

  public abstract loaded(): void;

  public abstract initNewModel(): T;

  set NewEndPointAPI(endpoint: string) {
    this.newEndPointAPI = endpoint;
    this.service.setURLRestAPI(this.newEndPointAPI);
  }

  public prepareParamsToLoad(): any {
    return ObjectUtil.combineValue(
      {
        limit: this.pagination.itemsPerPage,
        page: this.pagination.currentPage,
      },
      this.searchForm.value,
      true
    );
  }

  public load() {
    let params = this.prepareParamsToLoad();
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
      }, () => {
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  public create() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(this.getCreateModalComponent(), modalOptions);
    let modal: AbstractModalComponent<T> = <AbstractModalComponent<T>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.changeEndPointAPI(this.newEndPointAPI);
    let sub = modal.onHidden.subscribe((result: ModalResult<T>) => {
      if (result.success) {
        let itemCreated: T = result.data;
        this.list.unshift(itemCreated);
      }
      sub.unsubscribe();
    });
  }

  public edit(item: T, index: number) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(this.getEditModalComponent(), modalOptions);
    let modal: AbstractModalComponent<T> = <AbstractModalComponent<T>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    modal.changeEndPointAPI(this.newEndPointAPI);
    let sub = modal.onHidden.subscribe((result: ModalResult<T>) => {
      if (result.success) {
        let itemUpdated: T = result.data;
        this.list.splice(index, 1, itemUpdated);
      }
      sub.unsubscribe();
    });
  }

  public remove(item: T, index: number) {
    this.service.destroy(item['id']).subscribe(() => {
      this.list.splice(index, 1);
      this.service.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());

  }

  public pageChanged(event: any) {
    this.nextPage = event;
    this.pagination.currentPage = event.page;
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

  setRelatedModel(model: any) {
    this.relatedModel = model;
    this.loaded();
  }

  dismiss() {
    this.modal.hide();
  }

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

  copyToClipboard(data: string) {
    copyToClipboard(data);
    this.service.toastSuccessfully('', 'Copy lưu bộ nhớ đệm');
  }

  removeFilter() {
    this.searchForm.reset();
  }

  public viewModal(modalContent: any, model: any, onClose: any = null, options: any = {}) {
    let modalOptions = Object.assign(this.defaultModalOptions(), options);
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(modalContent, config);
    let modal: AbstractModalComponent<T> = <AbstractModalComponent<T>>modalRef.content;
    modal.setModel(model);
    if (onClose) {
      let sub = modal.onHidden.subscribe((result: ModalResult<T>) => {
        onClose(result);
        sub.unsubscribe();
      });
    }
  }

  public viewCRUDModal(modalContent: any, relatedModel: any, onClose: any = null, options: any = {}) {
    let modalOptions = Object.assign(this.defaultModalOptions(), options);
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(modalContent, config);
    let modal: AbstractCRUDModalComponent<any> = <AbstractCRUDModalComponent<any>>modalRef.content;
    modal.setRelatedModel(relatedModel);
    if (onClose) {
      let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
        onClose(result);
        sub.unsubscribe();
      });
    }
  }
}
