import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {ProductService} from './product.service';
import {ProductMeta} from './product.meta';
import {InventoryProductImportComponent} from '../inventory-product/inventory-product-import/inventory-product-import.component';
import {InventoryProductMeta} from '../inventory-product/inventory-product.meta';
import {InventoryProductService} from '../inventory-product/inventory-product.service';
import {InventoryProductCreateComponent} from '../inventory-product/inventory-product-create/inventory-product-create.component';
import {ProductCreateComponent} from './product-create/product-create.component';
import { AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent, DateTimeUtil, ExcelHelper, FieldForm, ModalResult, ObjectUtil, TitleService } from '../../core';
import { InventoryProductListComponent } from '../inventory-product/inventory-product-list/inventory-product-list.component';
import { InventoryProductEditComponent } from '../inventory-product/inventory-product-edit/inventory-product-edit.component';
import { InventoryProductAddComponent } from '../inventory-product/inventory-product-add/inventory-product-add.component';

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css'],
  providers: [ProductService, InventoryProductService],
})
export class ProductListComponent extends AbstractCRUDComponent<ProductMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Tồn kho';
  }

  getCreateModalComponent(): any {
    return ProductCreateComponent;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', backdrop: 'static', keyboard: false};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {backdrop: 'static', keyboard: false};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên sản phẩm', 'search', 'Nhập từ khóa','col-md-6'),
    ];
  }

  initNewModel(): ProductMeta {
    return new ProductMeta();
  }

  constructor(
    service: ProductService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private inventoryProductService: InventoryProductService
  ) {
    super(service, modal, builder);
  }

  export() {
    this.inventoryProductService.export({}).subscribe(resp => {
      ExcelHelper.exportXLSXFile(resp, `ton_kho_${DateTimeUtil.today('YYYY_MM_DD_X')}`);
      this.service.toastSuccessfully('Xuất file tồn kho', 'Thành công');
    });
  }

  import() {
    const config = {ignoreBackdropClick: true};
    const modalRef = this.modalService.show(InventoryProductImportComponent, config);
    let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  printBarcode(item: InventoryProductMeta, i: number) {
    let quantityStr = prompt('Số lượng tem', '1');

    if (quantityStr == null || quantityStr == '') {
      return;
    }
    let quantity: number = +quantityStr;
    this.inventoryProductService.printBarcode(item.id, quantity).subscribe(data => {
      const winUrl = URL.createObjectURL(
        new Blob([data['src']], {type: 'text/html;charset=utf-8'})
      );

      const win = window.open(
        winUrl,
        'In mã barcode',
        `width=265,height=400,screenX=200,screenY=200`
      );
    });
  }

  public editInventoryProduct(item: ProductMeta, index1: number, inventory: InventoryProductMeta, index2: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(InventoryProductEditComponent, config);
    let modal: AbstractModalComponent<InventoryProductMeta> = <AbstractModalComponent<InventoryProductMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(inventory));
    let sub = modal.onHidden.subscribe((result: ModalResult<InventoryProductMeta>) => {
      if (result.success) {
        this.list[index1].inventories[index2] = ObjectUtil.combineValue(inventory, result.data);
      }
      sub.unsubscribe();
    });
  }

  public addInventoryProduct(item: ProductMeta, index1: number, inventory: InventoryProductMeta, index2: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(InventoryProductAddComponent, config);
    let modal: AbstractModalComponent<InventoryProductMeta> = <AbstractModalComponent<InventoryProductMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(inventory));
    console.log(ObjectUtil.clone(inventory));
    let sub = modal.onHidden.subscribe((result: ModalResult<InventoryProductMeta>) => {
      if (result.success) {
        this.list[index1].inventories[index2] = ObjectUtil.combineValue(inventory, result.data);
      }
      sub.unsubscribe();
    });
  }

  public goToPageNumber() {
    this.nextPage = Math.round(this.nextPage);
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (Math.round(this.nextPage) > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
}
