import { expect, Locator, Page } from '@playwright/test';
import BasePage from './basePage';

export default class LoginPage extends BasePage {
    readonly emailField: Locator;
    readonly passwordField: Locator;
    readonly loginButton: Locator;

    constructor(page: Page) {
        super(page);
        this.page = page;

        // Locators
        //this.emailField = this.page.getByRole('textbox', { name: 'Email address' })
        this.emailField = this.page.locator('input[type="email"]');
        this.passwordField = this.page.getByRole('textbox', { name: 'Password' })
        this.loginButton = this.page.getByRole('button', { name: 'Log in' })
    }

    async goto() {
        await this.page.goto('http://127.0.0.1:8000/login');
    }

    async assertAtPage() {
        await expect(this.page).toHaveURL('http://127.0.0.1:8000/login');
    }
}
