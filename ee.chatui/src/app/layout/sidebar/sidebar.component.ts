import {Component, OnInit} from '@angular/core';
import {APP_FEATURES} from '../../app.features';
import {Auth, AuthService} from '../../modules/auth';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css'],
  providers: [AuthService]
})
export class SidebarComponent implements OnInit {

  features: any[];
  user: Auth;

  constructor(private authService: AuthService) {
    this.features = APP_FEATURES;
  }

  ngOnInit() {
    this.user = this.authService.getProfile();
  }

}
