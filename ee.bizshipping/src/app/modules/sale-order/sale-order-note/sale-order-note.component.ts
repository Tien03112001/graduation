import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
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
      note_ship: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Ghi chú', 'note_ship', 'Nhập kí tự' ,5),
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

  noteShip() {
    let item: SaleOrderMeta = this.prepareParams();
    (<SaleOrderService>this.service).note(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }
}
