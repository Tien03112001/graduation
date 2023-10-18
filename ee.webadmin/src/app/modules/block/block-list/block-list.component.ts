import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {BlockMeta} from '../block.meta';
import {BlockService} from '../block.service';
import {BlockCreateComponent} from '../block-create/block-create.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {BlockViewComponent} from '../block-view/block-view.component';
import {WidgetService} from '../../widget/widget.service';
import {WidgetMeta} from '../../widget/widget.meta';

@Component({
  selector: 'app-block-list',
  templateUrl: './block-list.component.html',
  styleUrls: ['./block-list.component.css'],
  providers: [BlockService, WidgetService]
})
export class BlockListComponent extends AbstractCRUDModalComponent<BlockMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý block';
  }

  getCreateModalComponent(): any {
    return BlockCreateComponent;
  }

  getEditModalComponent(): any {
    return null;
  }

  getViewModalComponent(): any {
    return BlockViewComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  getViewModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      published: new FormControl(''),
      page_id: new FormControl(null, Validators.required),
    });
  }

  public initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm theo tên',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Tìm kiếm theo tên',
      },
    ];
  }

  initNewModel(): BlockMeta {
    return new BlockMeta();
  }

  initNewWidgetModel(): WidgetMeta {
    return new WidgetMeta();
  }

  constructor(
    service: BlockService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private widgetService: WidgetService
  ) {
    super(service, modal, modalService, builder);
  }

  loaded(): void {
    this.searchForm.controls['page_id'].setValue(this.relatedModel['id']);
    this.load();
  }

  public create() {
    const modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<BlockMeta> = <AbstractModalComponent<BlockMeta>>modalRef.content;
    let blockMeta = this.initNewModel();
    blockMeta.page_id = this.relatedModel['id'];
    modal.setModel(blockMeta);
    let sub = modal.onHidden.subscribe((result: ModalResult<BlockMeta>) => {
      if (result.success) {
        let itemCreated: BlockMeta = result.data;
        this.list.unshift(itemCreated);
      }
      sub.unsubscribe();
    });
  }

  upOrder(item: BlockMeta, i: number) {
    (<BlockService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: BlockMeta, i: number) {
    (<BlockService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }


  view(item: BlockMeta, i: number) {
    const modalRef = this.modalService.show(this.getViewModalComponent(), this.getViewModalComponentOptions());
    let modal: AbstractModalComponent<WidgetMeta> = <AbstractModalComponent<WidgetMeta>>modalRef.content;
    let widgetMeta = this.initNewWidgetModel();
    let sub = this.widgetService.loadByID(item.widget_id).subscribe(res => {
      widgetMeta.html = res.html;
      widgetMeta.css = res.css;
      widgetMeta.js = res.js;
      widgetMeta.view_html = res.view_html;
      modal.setModel(widgetMeta);
      sub.unsubscribe();
    });
  }

  public goToPageNumber() {
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (this.nextPage > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
}
