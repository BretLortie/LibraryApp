import { test, expect } from '@playwright/test';
import LandingPage from '../poms/Pages/LandingPage.ts';
import LoginPage from '../poms/Pages/LoginPage.ts';
import DashboardPage from '../poms/Pages/DashboardPage.ts';

test('login', async ({ page }) => {
    let landingPage = new LandingPage(page)
    let loginPage = new LoginPage(page);
    let dashboardPage = new DashboardPage(page);

    await landingPage.goto();
    await landingPage.assertAtPage(); //At landing page

    await landingPage.loginLink.click();
    await loginPage.assertAtPage(); //At login page

    await loginPage.emailField.fill('test@gmail.com');
    await loginPage.passwordField.fill('password');
    await loginPage.loginButton.click();

    await dashboardPage.assertAtPage(); //At dashboard page
});



//My website is hosted at: http://127.0.0.1:8000

//How to run the test:
//1. Make sure the Laravel application is running on http://127.0.0.
//2. Open a terminal and navigate to the project directory.
//3. Run the Playwright test using the command: npx playwright test e2e/Bret.spec.ts
//4. The test will navigate to the login page and assert that it is at the correct page.