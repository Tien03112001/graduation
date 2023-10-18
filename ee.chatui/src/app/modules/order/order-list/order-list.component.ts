import {Component, Input, OnChanges, SimpleChanges} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AbstractCRUDComponent, FieldForm, TitleService} from '../../../core';
import {OrderService} from '../order.service';
import {OrderCreateComponent} from '../order-create/order-create.component';
import {OrderEditComponent} from '../order-edit/order-edit.component';
import {OrderMeta} from '../order.meta';
import {ConversationMeta} from '../../conversation/conversation.meta';

@Component({
  selector: 'app-order-list',
  templateUrl: './order-list.component.html',
  styleUrls: ['./order-list.component.css'],
  providers: [OrderService]
})
export class OrderListComponent extends AbstractCRUDComponent<OrderMeta> implements OnChanges {

  content: string;

  @Input()
  conversation: ConversationMeta;

  onInit(): void {
  }

  ngOnChanges(changes: SimpleChanges) {
    this.searchForm.controls['conversation_id'].setValue(this.conversation.id);
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý tin nhắn';
  }

  getCreateModalComponent(): any {
    return OrderCreateComponent;
  }

  getEditModalComponent(): any {
    return OrderEditComponent;
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
      conversation_id: new FormControl(null, [Validators.required])
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): OrderMeta {
    return new OrderMeta();
  }

  constructor(
    service: OrderService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }


  sendOrder() {
    let orderMeta = new OrderMeta();
    orderMeta.content = this.content;
    orderMeta.conversation_id = this.conversation.id;
    this.service.store(orderMeta).subscribe((msg) => {
      this.list.push(msg);
      this.content = null;
    });
  }
}
