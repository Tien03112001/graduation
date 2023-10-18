import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {SaleOrderService} from '../sale-order.service';
import {SaleOrderMeta} from '../sale-order.meta';
import {AbstractModalComponent, FieldForm, ObjectUtil} from '../../../core';


@Component({
  selector: 'app-order-note-edit',
  templateUrl: './sale-order-note.component.html',
  styleUrls: ['./sale-order-note.component.css'],
  providers: [SaleOrderService]
})
export class SaleOrderNoteComponent extends AbstractModalComponent<SaleOrderMeta> {
  onInit(): void {
  }


  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      note: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Lí do', 'note', 'Nhập kí tự' ,5),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  note() {
    let item: SaleOrderMeta = this.prepareParams();
    this.close(ObjectUtil.combineValue(this.model, item));
  }
}
