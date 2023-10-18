import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {AbstractCRUDComponent, FieldForm, TitleService} from '../../../core';
import {ConversationMeta} from '../conversation.meta';
import {ConversationService} from '../conversation.service';
import {WebSocketService} from '../../../services/web-socket.service';

@Component({
  selector: 'app-conversation-list',
  templateUrl: './conversation-list.component.html',
  styleUrls: ['./conversation-list.component.css'],
  providers: [ConversationService, WebSocketService]
})
export class ConversationListComponent extends AbstractCRUDComponent<ConversationMeta> {

  conversationSelected: any;
  newMessage: any;

  onInit(): void {
    this.load();
    this.webSocketService.init();
    this.webSocketService.listen('conversations').subscribe((data) => {
      let type = data['type'];
      if (type == 'update_message') {
        let conversation = data['data']['conversation'];
        let isNew = true;
        let message = data['data']['message'];
        for (let i = 0; i < this.list.length; i++) {
          let c = this.list[i];
          if (c.id == conversation['id']) {
            this.list.splice(i, 1);
            this.list.unshift(conversation);
            isNew = false;
            break;
          }
        }
        if (isNew) {
          this.list.unshift(conversation);
        }
        if (this.conversationSelected && this.conversationSelected.id == conversation['id']) {
          this.newMessage = message;
        }
      }
    })
  }

  onDestroy(): void {
    this.webSocketService.disconnect();
  }

  getTitle(): string {
    return 'Hệ thống chat';
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
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): ConversationMeta {
    return new ConversationMeta();
  }

  constructor(
    service: ConversationService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private webSocketService: WebSocketService
  ) {
    super(service, modal, builder,);
  }

  clickDetailConversion(item: ConversationMeta, i: number) {
    this.conversationSelected = item;
  }
}
