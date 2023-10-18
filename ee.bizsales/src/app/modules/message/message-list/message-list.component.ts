import {Component, EventEmitter, Input, OnChanges, Output, SimpleChanges} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AbstractCRUDComponent, AppPagination, FieldForm, ObjectUtil, TitleService} from '../../../core';
import {MessageService} from '../message.service';
import {MessageMeta} from '../message.meta';
import {ConversationMeta} from '../../conversation/conversation.meta';
import * as moment from 'moment';

@Component({
  selector: 'app-message-list',
  templateUrl: './message-list.component.html',
  styleUrls: ['./message-list.component.css'],
  providers: [MessageService]
})
export class MessageListComponent extends AbstractCRUDComponent<MessageMeta> implements OnChanges {

  content: string;
  listConversation: any;

  @Input() conversation: ConversationMeta;
  @Input() newMessage: any;

  @Output() conversationChanged: EventEmitter<string> = new EventEmitter();

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

  load() {
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
        setTimeout(() => {
          var objDiv = document.querySelector('.chat-content');
          if (objDiv) {
            objDiv.scrollTop = objDiv.scrollHeight;
          }
        }, 500);
      }, () => {
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
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
      content: new FormControl(null, [Validators.maxLength(255), Validators.required, Validators.pattern('[^ ].*$')]),
      conversation_id: new FormControl(null, [Validators.required])
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Nhập tin', 'content', 'Nhập tin nhắn...')
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
      this.list.unshift(msg);
      this.listConversation = msg.conversation;
      this.conversationChanged.emit(this.listConversation);
      this.content = null;
    });
  }

  loadHistoryMessages() {
    let time = moment(this.list[this.list.length - 1].created_at).format('Y-MM-DD H:mm:ss');
    this.service.loadByParams({ conversation_id:this.conversation.id, created_at: time }).subscribe(message => {
      if (message['data'].length > 0) {
        for (let i of message['data']) {
          this.list.push(i);
        }
      }
    });
  }
}
