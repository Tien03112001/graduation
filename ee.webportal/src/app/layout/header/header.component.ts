import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {Auth, AuthService} from '../../modules/auth';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
  providers: [AuthService]
})
export class HeaderComponent implements OnInit {

  name: string = '';
  photoUrl: string;
  app_name: string = 'Quản trị viên';
  user: Auth;

  constructor(private router: Router, private authService: AuthService) {
  }

  ngOnInit() {
    this.user = this.authService.getProfile();
  }

  logout() {
    this.authService.logOut();
  }
}
