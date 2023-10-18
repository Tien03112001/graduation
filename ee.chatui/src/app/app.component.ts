import { Component, OnInit } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { environment } from '../environments/environment';
import { WebSocketService } from './services/web-socket.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [WebSocketService]
})
export class AppComponent implements OnInit {
  title = 'app';

  constructor(private titleService: Title) {
    this.titleService.setTitle(environment.title);
  }

  ngOnInit(): void {
  }

}
