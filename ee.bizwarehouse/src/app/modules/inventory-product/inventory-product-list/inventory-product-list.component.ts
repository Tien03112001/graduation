import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { ModalResult, ObjectUtil } from '../../../core';
import { AbstractCRUDModalComponent, AbstractModalComponent } from '../../../core/crud';
import { TitleService } from '../../../core/services';
import { InventoryProductCreateComponent } from '../inventory-product-create/inventory-product-create.component';
import { InventoryProductEditComponent } from '../inventory-product-edit/inventory-product-edit.component';
import { InventoryProductMeta } from '../inventory-product.meta';
import { InventoryProductService } from '../inventory-product.service';

@Component({
  selector: 'app-inventory-product-list',
  templateUrl: './inventory-product-list.component.html',
  styleUrls: ['./inventory-product-list.component.css'],
  providers: [InventoryProductService]
})
export class InventoryProductListComponent extends AbstractCRUDModalComponent<InventoryProductMeta> {
  formBuilder: any;

  getTitle(): string {
    return 'Quản lý role';
  }

  getCreateModalComponent(): any {
    return InventoryProductCreateComponent;
  }

  getEditModalComponent(): any {
    return InventoryProductEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): InventoryProductMeta {
    let inventory: InventoryProductMeta = new InventoryProductMeta();
    inventory.product_id = this.relatedModel.id;
    return inventory;
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loaded(): void {
    this.load();
  }

  constructor(
    service: InventoryProductService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, modalService, builder);
  }

  public load() {
    let param = {
      product_id: this.relatedModel.id,
    };
    this.service.loadByParams(param).subscribe((res: InventoryProductMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  createInventory() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let inventory = new InventoryProductMeta();
    let modal: AbstractModalComponent<InventoryProductMeta> = <AbstractModalComponent<InventoryProductMeta>>modalRef.content;
    inventory.product_id = this.relatedModel.id;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<InventoryProductMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  public editInventory(item: InventoryProductMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(InventoryProductEditComponent, config);
    let modal: AbstractModalComponent<InventoryProductMeta> = <AbstractModalComponent<InventoryProductMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    let sub = modal.onHidden.subscribe((result: ModalResult<InventoryProductMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  printBarcode(item: InventoryProductMeta, i: number) {
    let quantityStr = prompt('Số lượng tem', '1');

    if (quantityStr == null || quantityStr == '' || quantityStr < '1'  ) {
      return;
    }
    let quantity: number = +quantityStr;
    (<InventoryProductService>this.service).printBarcode(item.id, quantity).subscribe(data => {
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
}
