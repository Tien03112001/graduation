import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {FieldForm, ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {StructuredDataListComponent} from '../../structured-data/structured-data-list/structured-data-list.component';
import {StructuredDataMeta} from '../../structured-data/structured-data.meta';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {PageService} from '../../page/page.service';
import {PageMeta} from '../../page/page.meta';

@Component({
  selector: 'app-page-list',
  templateUrl: './page-seometa.component.html',
  styleUrls: ['./page-seometa.component.css'],
  providers: [PageService, MetaDataService]
})
export class PageSeometaComponent extends AbstractCRUDComponent<PageMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Tối ưu seo page';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      published: new FormControl(''),
    });
  }

  public initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm theo tên trang',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Tìm kiếm theo tên trang',
      },
    ];
  }

  initNewModel(): PageMeta {
    return new PageMeta();
  }

  editMeta(item: PageMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataEditComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    modal.setModel(item.meta);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  createMeta(item: PageMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataCreateComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new MetaDataMeta();
    metaData.metaable_type = 'pages';
    metaData.metaable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  openStructured(item: PageMeta, index: number) {
    const modalRef = this.modalService.show(StructuredDataListComponent, {ignoreBackdropClick: true});
    const modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    const metaData = new StructuredDataMeta();
    metaData.structureble_type = 'pages';
    metaData.structureble_id = item.id;
    modal.setRelatedModel(metaData);
    const sub = modal.onHidden.subscribe((result: ModalResult<StructuredDataMeta[]>) => {
      if (result.success) {
        this.list[index].structure_datas = result.data;
      }
      sub.unsubscribe();
    });
  }


  constructor(
    service: PageService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
