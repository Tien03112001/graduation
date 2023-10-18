import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {Auth, AuthService} from '../../modules/auth';
import {StorageUtil} from '../../core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
  providers: [AuthService]
})
export class HeaderComponent implements OnInit {

  name: string = '';
  app_name: string = 'Hệ thống Ezi System';
  user: Auth;

  constructor(private router: Router, private authService: AuthService) {
  }

  ngOnInit() {
    this.user = this.authService.getProfile();
    console.log(this.user);
  }

  logout() {
    StorageUtil.clear();
    setTimeout(() => {
      this.router.navigateByUrl('login');
    }, 500);
  }
}
