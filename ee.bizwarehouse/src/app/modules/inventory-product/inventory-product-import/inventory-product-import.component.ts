import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {InventoryProductService} from '../inventory-product.service';
import {InventoryProductMeta} from '../inventory-product.meta';
import { AbstractModalComponent, DateTimeUtil, ExcelHelper, FieldForm } from '../../../core';


@Component({
  selector: 'app-inventory-product-import',
  templateUrl: './inventory-product-import.component.html',
  styleUrls: ['./inventory-product-import.component.css'],
  providers: [InventoryProductService]
})
export class InventoryProductImportComponent extends AbstractModalComponent<InventoryProductMeta> {
  onInit(): void {
  }


  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      file: new FormControl(null, Validators.required)
    });
  }

  onFileUploadChange(event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      this.formGroup.controls['file'].setValue(input.files[0]);
    }
  }

  initFieldForm(): FieldForm[] {
    return [];
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

  import() {
    this.service.import(this.formGroup.get('file').value,{}).subscribe(res => {
      this.service.toastSuccessfully('Nhập file', 'Thành công');
      this.close({});
    },() => this.service.toastFailedCreated());;
  }
}
