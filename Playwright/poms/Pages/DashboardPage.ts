import { expect, Locator, Page } from '@playwright/test';
import BasePage from './basePage';

export default class DashboardPage extends BasePage {
    // readonly registerLink: Locator;
    // readonly loginLink: Locator;

    constructor(page: Page) {
        super(page);
        this.page = page;

        // Locators
        // this.registerLink = this.page.getByRole('link', { name: 'Register' });
        // this.loginLink = this.page.getByRole('link', { name: 'Log in' })
    }

    async goto() {
        await this.page.goto('http://127.0.0.1:8000/dashboard');
    }

    async assertAtPage() {
        await expect(this.page).toHaveURL('http://127.0.0.1:8000/dashboard');
    }
}
