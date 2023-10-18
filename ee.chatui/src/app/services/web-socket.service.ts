import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {AbstractCRUDService, StorageUtil, TitleService} from '../core';
import {ToasterService} from 'angular2-toaster';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment';

declare const io;

@Injectable()
export class WebSocketService extends AbstractCRUDService<any> {


  socket: any;
  token: string;

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'socket', 'sockets');
    this.token = StorageUtil.get('token');
  }

  init() {
    this.socket = io(environment.chatws_url, {
      auth: {
        token: this.token
      },
    });
  }

  listen(eventName: string) {
    if (this.socket) {
      return new Observable((subscriber) => {
        this.socket.on(`${this.token}_${eventName}`, (data) => {
          subscriber.next(data);
        });
      });
    } else {
      this.toastWarning('Socket chưa khởi tạo');
    }

  }

  emit(eventName: string, data: any) {
    if (this.socket) {
      this.socket.emit(eventName, data);
    } else {
      this.toastWarning('Socket chưa khởi tạo');
    }
  }

  disconnect() {
    if (this.socket) {
      this.socket.disconnect();
    }
  }

}
