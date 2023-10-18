import {Component, Input, OnChanges, SimpleChanges} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AbstractCRUDComponent, FieldForm, TitleService} from '../../../core';
import {MessageService} from '../message.service';
import {MessageMeta} from '../message.meta';
import {ConversationMeta} from '../../conversation/conversation.meta';

@Component({
  selector: 'app-message-list',
  templateUrl: './message-list.component.html',
  styleUrls: ['./message-list.component.css'],
  providers: [MessageService]
})
export class MessageListComponent extends AbstractCRUDComponent<MessageMeta> implements OnChanges {

  content: string;

  @Input() conversation: ConversationMeta;
  @Input() newMessage: any;

  onInit(): void {
  }

  ngOnChanges(changes: SimpleChanges) {
    for (let prop in changes) {
      if (prop == 'conversation') {
        this.searchForm.controls['conversation_id'].setValue(this.conversation.id);
        this.load();
      } else if (prop == 'newMessage') {
        if (this.newMessage) {
          if (this.newMessage.conversation_id == this.conversation.id) {
            this.list.push(this.newMessage);
          }
        }
      }
    }
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý tin nhắn';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
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

  initNewModel(): MessageMeta {
    return new MessageMeta();
  }

  constructor(
    service: MessageService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }


  sendMessage() {
    let messageMeta = new MessageMeta();
    messageMeta.content = this.content;
    messageMeta.conversation_id = this.conversation.id;
    this.service.store(messageMeta).subscribe((msg) => {
      this.list.push(msg);
      this.content = null;
    });
  }
}
