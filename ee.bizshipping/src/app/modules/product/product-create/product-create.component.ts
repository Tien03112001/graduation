import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import { AbstractModalComponent, FieldForm } from '../../../core';
import {ProductMeta} from '../product.meta';
import {ProductService} from '../product.service';

@Component({
  selector: 'app-product-create',
  templateUrl: './product-create.component.html',
  styleUrls: ['./product-create.component.css'],
  providers: [ProductService]
})
export class ProductCreateComponent extends AbstractModalComponent<ProductMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      code: new FormControl(null, [Validators.required]),
      name: new FormControl(null, [Validators.required]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      {
        label: 'Mã sản phẩm',
        type: 'input',
        typeof: 'text',
        formControl: 'code',
        placeHolder: 'VD: 6000020'
      },
      {
        label: 'Tên sản phẩm',
        type: 'input',
        typeof: 'text',
        formControl: 'name',
        placeHolder: 'Nhập tên'
      },
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ProductService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}
