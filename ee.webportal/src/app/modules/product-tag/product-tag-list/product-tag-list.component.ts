import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ProductTagMeta} from '../product-tag.meta';
import {ProductTagService} from '../product-tag.service';
import {ProductTagCreateComponent} from '../product-tag-create/product-tag-create.component';
import {ProductTagEditComponent} from '../product-tag-edit/product-tag-edit.component';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';

@Component({
  selector: 'app-product-tag',
  templateUrl: './product-tag-list.component.html',
  styleUrls: ['./product-tag-list.component.css'],
  providers: [ProductTagService]
})
export class ProductTagListComponent extends AbstractCRUDComponent<ProductTagMeta> {

  onInit(): void {
    this.load();

  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý tag sản phẩm';
  }

  getCreateModalComponent(): any {
    return ProductTagCreateComponent;
  }

  getEditModalComponent(): any {
    return ProductTagEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): ProductTagMeta {
    return new ProductTagMeta();
  }

  createMeta(item: ProductTagMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataCreateComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new MetaDataMeta();
    metaData.metaable_type = 'product_tags';
    metaData.metaable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  editMeta(item: ProductTagMeta, index: number) {
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

  constructor(
    service: ProductTagService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
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
