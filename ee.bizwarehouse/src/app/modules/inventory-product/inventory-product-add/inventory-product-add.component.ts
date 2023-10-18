import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import { AbstractModalComponent, FieldForm } from '../../../core';
import {InventoryProductMeta} from '../inventory-product.meta';
import {InventoryProductService} from '../inventory-product.service';

@Component({
  selector: 'app-inventory-product-add',
  templateUrl: './inventory-product-add.component.html',
  styleUrls: ['./inventory-product-add.component.css'],
  providers: [InventoryProductService]
})
export class InventoryProductAddComponent extends AbstractModalComponent<InventoryProductMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      add_quantity: new FormControl(0, [Validators.required, Validators.min(0), Validators.pattern('^(?=.*[0-9]+)[0-9]*$'), Validators.max(9999999999)]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Số lượng thêm', 'add_quantity', '0'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: InventoryProductService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  add() {
    let item: InventoryProductMeta = this.prepareParams();
    console.log(item);
    (<InventoryProductService>this.service).add(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }

}
