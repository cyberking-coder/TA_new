<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds the public informational pages reachable from the header + footer
 * nav. Idempotent (firstOrNew); re-running it will NOT overwrite any field
 * an admin has already edited to something non-empty — it only fills in
 * blanks.
 *
 * Source: scraped from jointerritorialarmy.com via screenshots in
 * data-import/TA_content.
 */
class StaticPagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $slug => $data) {
            $page = Page::firstOrNew(['slug' => $slug]);

            foreach ($data as $field => $value) {
                $current = (string) $page->{$field};
                // Overwrite when the slot is blank OR when it still holds the
                // placeholder text from an earlier seeder. Real admin edits
                // (anything else non-blank) are preserved.
                if (blank($current) || $this->isPlaceholder($current)) {
                    $page->{$field} = $value;
                }
            }

            if (blank($page->status)) $page->status = 'published';
            if (blank($page->published_at)) $page->published_at = now();

            $page->save();
        }
    }

    private function isPlaceholder(string $value): bool
    {
        $needles = [
            'This page is a placeholder',
            'Real content is maintained by staff in the admin panel',
            'For the authoritative version, check the official Territorial Army website',
        ];
        foreach ($needles as $n) {
            if (stripos($value, $n) !== false) return true;
        }
        return false;
    }

    /** @return array<string, array<string, string>> */
    private function pages(): array
    {
        return [
            'contact-us' => [
                'title' => 'Contact Us',
                'menu_label' => 'Contact Us',
                'seo_title' => 'Contact Us — Territorial Army',
                'seo_description' => 'Contact the Territorial Army Group Headquarters by zone, plus book-support WhatsApp / email.',
                'body' => <<<'MD'
For any queries regarding notification, clarification on eligibility conditions, how to apply, selection procedure, application form, exam centre, Territorial Army Commission (TAC) written exam, submission of documents, SSB interview dates, joining, embodiment for training, military service embodiment, service benefits, terms and conditions of service etc. contact the concerned command given below.

## TA Group Headquarters

**Western Command** — Building No.750, Sector 8-B, Chandigarh-160018. Ph: 0172-2547864, 0172-258798
**Central Command** — Lucknow-226002, Uttar Pradesh. Ph: 0522-2482278
**Eastern Command** — Fort William, Kolkata-21, West Bengal. Ph: 033-22313227
**Southern Command** — Pune-411001, Maharashtra. Ph: 020-26880231, 020-26880123, 7768002503
**Northern Command** — Udhampur (J&K) Pin-182101. Ph: 01992-243592

## Book & study-material support

For queries regarding the Territorial Army Officer Commission (TAC) examination book and study material: email **taexambook@gmail.com** or WhatsApp **9017516001**.
MD,
            ],

            'disclaimer' => [
                'title' => 'Disclaimer',
                'menu_label' => 'Disclaimer',
                'seo_title' => 'Disclaimer — Territorial Army',
                'seo_description' => 'Disclaimer covering the use of this website and the information provided.',
                'body' => <<<'MD'
The information contained in this website is for general information and exam purposes only. The website has been created for providing books for students who are aspiring to appear in Territorial Army officer entry exams in India.

Through this website you are able to link to other websites which are not under our control. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.

The information is provided while we endeavour to keep the information up to date and correct; we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.

Every effort is made to keep the website up and running smoothly. However, we take no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.
MD,
            ],

            'privacy' => [
                'title' => 'Privacy Policy',
                'menu_label' => 'Privacy Policy',
                'seo_title' => 'Privacy Policy — Territorial Army',
                'seo_description' => 'How we collect, use and protect your personal information on this site.',
                'body' => <<<'MD'
Your privacy is very important to us. We do not publish, sell or rent your personal information to third parties for their marketing purposes without your explicit consent. Please read this privacy policy to learn more about the ways in which we use and protect your personal information.

## 1. Your Privacy — Our Commitment

We are extremely proud of our commitment to protect your privacy. We value your trust in us. We will work hard to earn your confidence so that you can enthusiastically use our services and recommend us to friends and family. Please read the following policy to understand how your personal information will be treated as you make full use of our site.

## 2. Our Privacy Guarantee

We promise that we will not sell or rent your personal information to third parties for their marketing purposes without your explicit consent. From time to time we may reveal general statistical information about our website and visitors, such as number of visitors, number and type of goods and services purchased, etc. Your trust and confidence are our highest priority.

## 3. Information we collect

When you use our site, we collect and store your personal information from you. Our primary goal is to provide a safe, efficient, smooth and customized experience. This allows us to provide services and features that most likely meet your needs, and to customize our site to make your experience safer and easier. Importantly, we only collect personal information about you that we consider necessary for achieving this purpose.

In general, you can browse the site without telling us who you are or revealing any personal information about yourself. Once you give us your personal information, you are not anonymous to us. To fully use our site, you will need to register using our online registration form, where you may be required to provide us with your name, date of birth, contact number, email id, user id, password, residence / place of business information, billing information, shipping information, bank account details and other personal information as indicated on the forms throughout the site. Where possible, we indicate which fields are required and which fields are optional. You always have the option to not provide information by choosing not to use a particular service or feature on the site.

We may automatically track certain information about you based upon your behavior on our site. We use this information to do internal research on our users' demographics, interests, and behavior to better understand, protect and serve our users. This information is compiled and analyzed on an aggregated basis. This information may include the URL that you just came from, which URL you next go to, your computer browser information, and your IP address.

We use data collection devices such as "cookies" on certain pages of the site to help analyse our web-page flow, measure promotional effectiveness, and promote trust and safety. Cookies are small files placed on your hard drive that assist us in providing our services. Most cookies are "session cookies," meaning that they are automatically deleted from your hard drive at the end of a session. You are always free to decline our cookies if your browser permits, although in that case you may not be able to use certain features on the site and you may be required to reenter your password more frequently during a session.

## 4. Our use of your information

We use your personal information to facilitate the services you request. We use your personal information in the file we maintain about you, and other information we obtain from your current and past activities on the site to: resolve disputes; troubleshoot problems; help promote safe trading; collect fees owed; measure consumer interest in the services provided by us, inform you about online and offline offers, products, services, and updates; customize your experience; detect and protect us against error, fraud and other criminal activity; enforce our User Agreement; and as otherwise described to you at the time of collection.

You agree that we may use personal information about you to improve our marketing and promotional efforts, to analyze site usage, improve the site's content and product offerings, and customize the site's content, layout, and services. These uses improve the site and better tailor it to meet your needs, so as to provide you with a smooth, efficient, safe and customized experience while using the site.

## 5. Our Disclosure of your information

We may share your information with trusted business partners who help us deliver services to you — payment processors, delivery partners, SMS and email service providers — bound by strict confidentiality obligations. We will also disclose your personal information if required by law, to enforce our User Agreement, or to protect our rights, property or safety.

## Contact

For privacy questions email **taexambook@gmail.com**.
MD,
            ],

            'terms' => [
                'title' => 'Terms & Conditions',
                'menu_label' => 'Terms & Conditions',
                'seo_title' => 'Terms & Conditions — Territorial Army',
                'seo_description' => 'Terms governing use of this website, orders, shipments and limitations of liability.',
                'body' => <<<'MD'
## Terms of Use

We provide services to you subject to the notices, terms and conditions set forth in this agreement. In addition, when you use any of our services (customer reviews, book orders, study-access) you will be subject to the rules, guidelines, policies, terms and conditions applicable to such service, all of which are incorporated by reference into this agreement.

We reserve the right to change this site and these terms and conditions at any time. We retain the right to deny access to anyone we believe to have violated any of these terms. By accessing, browsing or otherwise using the site you indicate your agreement to all the terms and conditions in this agreement, so please read this agreement carefully before proceeding.

## Content Posted On the Site

You are responsible for any notes, messages, e-mails, billboard postings, photos, drawings, profiles, opinions, ideas, images, videos, audio files or other materials or information posted or transmitted to the site (collectively "Content"). Such Content will become our property throughout the universe.

You shall be entitled, in accordance with our Privacy Policy, to use the Content or any of its elements for any type of use forever, including but not limited to promotional and advertising purposes and in any media whether now known or hereafter devised, including the creation of derivative works. You agree that any Content you post may be used by us, consistent with our Privacy Policy and Rules of Conduct on the Site as mentioned here, and you are not entitled to any pay mentor other compensation for such use.

## Rules of Conduct On Site

You may not post, distribute, or reproduce in any way any copyrighted material, trademarks, or other proprietary information without obtaining the prior written consent of the owner of such proprietary rights. If it may done by anyone than, it's an infringement of intellectual property rights.

You must use the site in a manner consistent with any and all applicable law and regulations. Illegal and/or unauthorized uses of the site, including without limitation, collecting usernames and/or email addresses of members by electronic or other means for the purpose of sending unsolicited email and unauthorized framing of or linking to the site, are not permitted.

## Links

This site may contain links to other web sites or resources. Because we have no control over such sites and resources, you acknowledge and agree that we are not responsible for the availability of such sites or resources, and do not endorse and are not responsible or liable for any content, advertising, products, or other materials on or available from such sites or resources.

## Our Products

We offer products related to Territorial Army Commission (TAC) examination — printed books and buyer-only study PDFs. Product descriptions are provided to help you make informed decisions. However, we do not warrant that product descriptions or other content of this site is accurate, complete, reliable, current, or error-free. If a product offered by us is not as described, your sole remedy is to return it unused.

## Payment

While availing any of the payment methods offered by us, we are not responsible or take no liability of whatsoever nature in respect of any loss or damage arising directly or indirectly to you out of the decline due to: lack of authorization for any transaction/s, exceeding the preset limit mutually agreed by you and between your bank, any payment issues arising out of the transaction, or decline of transaction for any other reason/s. All payments made against the purchases/services on our site by you shall be compulsorily in Indian Rupees acceptable by the bank of India. We do not accept any other form of currency with respect to the purchases.

## Applicable Law

This agreement shall be governed by and interpreted and construed in accordance with the laws of India. The place of jurisdiction shall be Chandigarh.

## Trademark, Copyright and Restriction

This site is controlled and operated by us. All material on this site, including images, illustrations, audio clips and video clips, is protected by copyrights, trademarks and other intellectual property rights that are owned and controlled by us or by other parties that have licensed their material to us. Material on this site, whether owned, operated, licensed or controlled by us is solely for your personal, non-commercial use. You must not copy, reproduce, republish, upload, post, transmit or distribute such material in any way, including by e-mail or other electronic means and whether directly or indirectly, and you must not assist any other person to do so.

## Your Account

You agree that any use on the site is at your own risk and we are not responsible for it. You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer, and you agree to accept responsibility for all activities that occur under your account or password. Products sold on our site are not for people below age 18; however any products available for children below age 18 can be bought by adults.

## Risk of loss

All items purchased from us are made pursuant to a shipment contract. This means that the risk of loss and title for such items pass to you upon our delivery to the carrier.

## Pricing / Typographical error

While product listings try to be as accurate as possible, however, due to any technical, typographical or other product information received from our suppliers, prices of a product may vary. We will try to be accurate, however if you come across any difference in pricing on the site, Website and post orders, we request you to contact us within 48 (Forty Eight) hours of placing your order. We shall refund the difference amount within 10-15 business days of intimation. The mode of refund will be the same as at the time of placing the order. Cash on Delivery order refunds will be done by cheque. If the problem comes across any such difference in pricing, it has all the rights to rectify the same or cancel the order.

## Cancellation of order

We reserve the right to cancel any order without any explanation for doing so, under situations where we are not able to meet the requirement of the order placed or order so placed and cancelled does not comply with our policy or for any other reason. However, we will ensure that any communication of cancellation of an order, so cancelled, is intimated within appropriate time to the concerned person.

## Limitation of Liability

In no event shall we be liable for any special, incidental, indirect or consequential damages of any kind in connection with this agreement, even if we have been informed in advance of the possibility of such damages.

## Our Contact

Please send any questions or comments (including all inquiries unrelated to copyright infringement) regarding this site to **territorialarmyexambooks[at]gmail[dot]com**.
MD,
            ],

            'shipping-and-delivery' => [
                'title' => 'Shipping & Delivery',
                'menu_label' => 'Shipping & Delivery',
                'seo_title' => 'Shipping & Delivery — Territorial Army',
                'seo_description' => 'All-India shipping terms, delivery times and costs for the Territorial Army exam book.',
                'body' => <<<'MD'
## Where do we deliver?

We deliver to all locations in India where there is reach of the Indian Postal Network. At present, we do **NOT** make any international shipments / deliveries.

## How much does shipping and delivery cost?

Shipping charges are not applicable.

## How long does the shipping take?

Major destinations deliveries normally take **4–8 working days**. We dispatch books through Indian Registered Post, a Government of India undertaking. There is NO online tracking currently available for book parcels sent by Indian Registered Post.

Delivery through India Post normally takes longer and could extend to more than 2 weeks for some locations and rare situations. Kindly be patient — inform us at **territorialarmyexambooks@gmail.com** if there is an inordinate delay and we will follow up with India Post.

**Note:** Books ordered on Saturday after 12 p.m. will take an extra day to ship due to the weekend.
MD,
            ],

            'about-us' => [
                'title' => 'About Us',
                'menu_label' => 'About Us',
                'seo_title' => 'About Us — Territorial Army',
                'seo_description' => 'Independent online store for Territorial Army officer entry examination books and study material.',
                'body' => <<<'MD'
**www.territorialarmy.in** is an online store which provides books and online study material for Territorial Army Online Written Examination. The site has been created for the purpose of providing facility to get online study material and books for Territorial Army Officer Entrance Examination, with options of paying through UPI, Debit Card, Credit Card, Cash Card, Net Banking etc., processed through secure and trusted gateways. We are committed to ensure 100% Purchase Protection. We make significant efforts to fulfill the order and deliver through Indian Registered Post.

**www.territorialarmy.in** is NOT a website to apply online for officer entry for civilian candidates. **www.joinindianarmy.nic.in** is the official website to apply online for officer entry for civilian candidates.
MD,
            ],

            'refund-and-cancellation' => [
                'title' => 'Refund & Cancellation Policy',
                'menu_label' => 'Refund & Cancellation Policy',
                'seo_title' => 'Refund & Cancellation Policy — Territorial Army',
                'seo_description' => 'How to cancel orders and request refunds before or after shipment.',
                'body' => <<<'MD'
Kindly send us an email giving details of cancellation, clearly mentioning your order number.

## Before Shipment

Cancellation is done before shipment — the entire amount will be refunded.

## After Shipment

Cancellation will not be done after shipment. After shipment you can refuse to accept the book from the postman while delivery through Indian Registered Post; in this case the book will be returned to origin and we will process your refund or return unopened in its original packing within 7 days of delivery. Kindly send an email requesting return and you would be required to send the book back to us by Indian Registered Post and send us an email giving details of dispatch clearly mentioning your book order number. We will refund the amount after receipt of the book minus our original delivery charges. Refund may vary from **7 to 21 working days**, depending on the issuing bank / payment processor to pass credit for the said refund.

Removal or reduction in order quantity will be governed by the Cancellation Policy mentioned above.

You cannot increase the order quantity. You need to place a new order for additional requirements.

## Contact

For order assistance email us at **TAExamBook@gmail.com**
WhatsApp **9017516001**
MD,
            ],

            'join-as-jawan' => [
                'title' => 'Join as a Jawan / JCO',
                'menu_label' => 'Join as a Jawan / JCO',
                'seo_title' => 'Join as a Jawan / JCO — Territorial Army',
                'seo_description' => 'Eligibility, process, recruitment zones and TA Group Headquarters contacts for Jawan / JCO entry.',
                'body' => <<<'MD'
## Eligibility

- **Age:** 18 to 42 years
- **Education:** Matriculation (dispensation for lower education qualification in specified cases exists).

## Process

Applicant to report to the unit or the Recruiting Office on designated dates as advertised in Newspaper and the Indian Army Website for tests as per laid down procedures. Selected candidates have to go through the Medical Board and Police Verification.

**Click below for latest recruitments of JCO and Jawan schedule:**

1. Advertisement for recruitment in Infantry Battalion (Territorial Army) for units under Territorial Army Group Headquarters Southern Command.
2. Recruitment Rally in 137 Composite Ecological Task Force Battalion (Territorial Army) 39 Gorkha Rifles.
3. Advertisement for recruitment in Infantry Battalion (Territorial Army).
4. Advertisement for recruitment in Infantry Battalion (Territorial Army) units Central Command.
5. Advertisement for recruitment in Infantry Battalions / Home & Hearth Battalions (Territorial Army).
6. Territorial Army Group Headquarter, Northern Command advertisement for recruitment in Infantry Battalion / Home & Hearth Battalions / Ikhwan Battalion / Engineer Regiment.
7. Bihar & Uttar Pradesh Territorial Army recruitment rally.

**Jawan Recruitment Schedule:** https://joinindianarmy.nic.in/latest-rally-jcos-or.htm

**Note:** Recruitment in TA is carried out on a Zonal basis.

## Recruitment Zones

1. **Zone-I:** Haryana, Himachal Pradesh, Jammu & Kashmir, Punjab and the Union Territories of Delhi & Chandigarh.
2. **Zone-II:** Bihar, Madhya Pradesh, Orissa, Uttar Pradesh, Uttarakhand, Jharkhand and Chhattisgarh.
3. **Zone-III:** Assam, Meghalaya, Manipur, Tripura, Nagaland, Sikkim, West Bengal, Mizoram, Arunachal Pradesh and Andaman & Nicobar Islands.
4. **Zone-IV:** Andhra Pradesh, Gujarat, Kerala, Tamil Nadu, Maharashtra, Rajasthan, Karnataka and the Union Territories of Dadra and Nagar Haveli, Goa, Daman & Diu, Lakshadweep and Pondicherry.

## Additional Directorate General of Territorial Army

GS Branch, L Block, Church Road, New Delhi-01

### Join as a JCO

**Call on Queries Related to Junior Commissioned Officers and Other Ranks:** 011-23092827

### Territorial Army Group Headquarters

- **Western Command** — Building No 750, Sector 8-B, Chandigarh (UT). Tele: 0172-2781957, 0172-2781859
- **Central Command** — Lucknow. Tele: 0522-2292245, 0522-2292250
- **Eastern Command** — Fort William, Kolkata. Tele: 033-22313227
- **Southern Command** — Pune. Tele: 020-2680123
- **Northern Command** — Udhampur. Tele: 01992-243592
MD,
            ],

            'officer-civilian-candidates' => [
                'title' => 'For Civilian Candidates',
                'menu_label' => 'For Civilian Candidates',
                'seo_title' => 'Join as an Officer — For Civilian Candidates — Territorial Army',
                'seo_description' => 'Eligibility, exam centres, application steps and pay scales for civilian officer entry in the Territorial Army.',
                'body' => <<<'MD'
Applications are invited from gainfully employed citizens for an opportunity of donning the uniform and serving the nation as Territorial Army Officers, based on the concept of enabling motivated young citizens to serve in a military environment without having to sacrifice their primary professions. You can serve the nation in two capacities — as a civilian and as a soldier. No other option allows you such an expanse of experiences.

The candidates applying for the examination should ensure that they fulfill the eligibility conditions for the commission. Their candidature at all the stages of the commissioning process will be purely provisional subject to satisfying the prescribed eligibility conditions. **Mere issue of Admit Card to the candidate will not imply that his candidature has been finally cleared by the Territorial Army Directorate.** Verification of eligibility conditions with reference to original documents will be taken up only after the candidate has qualified for the Service Selection Board interview.

## Conditions of Eligibility

- **(a) Nationality:** Only citizens of India (male and female).
- **(b) Age Limits:** 18 to 42 years as on last day of filing of application i.e. should have attained minimum 18 years and should not be more than 42 Years on 19 May 2026.
- **(c) Educational Qualifications:** Graduate from any recognized university.
- **(d) Physical Standards:** A candidate must be physically and medically fit in all respects.
- **(e) Employment:** Gainfully Employed (Central Govt. / State Govt. / Semi Govt. / PSU / Pvt. Sector / Self Employed / Own Business).

**Note:** Serving members of the Regular Army / Navy / Air Force / Police / GREF / Para Military and like forces are not eligible.

**Date of Online Computer Based Test (CBT) Entrance Examination:** 12 July 2026

The date of holding the examination as mentioned above is liable to be changed at the discretion of the competent authority.

## Centres of Examination

The examination will be held PAN India.

**Disclaimer:** The Territorial Army Directorate has the right to conduct the Computer Based Test (CBT) at all the cities or any of the cities depending upon the number of candidates applied and other compulsions.

## Territorial Army Group Headquarters

| Zone | Command | Address |
|------|---------|---------|
| 1 | Western | Building No.750, Sector 8-B, Chandigarh-160018. Ph 0172-2547864 |
| 2 | Central | Lucknow (UP) – 226002. Ph 0522-2482278, 8953401641 |
| 3 | Eastern | Fort William, Kolkata (WB) – 700021. Ph 033-22313227 |
| 4 | Southern | Pune-411001. Ph 7768002503, 020268812 |
| 5 | Northern | Udhampur (J&K) – 182101. Ph 01992 243592 |

## How to Apply

Online Registration and Applications can be filled from **20 April 2026 (10:00 Hours) to 19 May 2026 (23:55 Hours)** after which the link will be disabled. Candidates are required to apply online using the link **https://www.indianarmy.nic.in** till **19 May 2026 till 23:55 PM**. Brief instructions for filling up the online Application Form (IAF(TA)-9 (Revised) Part-1) have been given on the website.

## Fee Details

Candidates are required to pay a fee of **Rs 500/- (Rupees five hundred only)**. Candidates should note that payment of examination fee can be made only through the modes prescribed at website. Payment of fee through any other mode is neither valid nor acceptable. Applications submitted without the prescribed fee / mode shall be summarily rejected. Fee once paid shall not be refunded under any circumstances nor can the fee be held in reserve for any other examination or selection.

## Instruction to the Candidate for Filling Online Application

- The Online application can be filled from **20 April 2026 to 19 May 2026** after which link will be disabled.
- The applicants must ensure that while filling their Application Form, they are providing their valid and active E-Mail IDs and mobile number as the concerned authorities will use electronic mode of communication while contacting them at different stages of examination process.
- Candidates are advised to apply online well in time without waiting for the last date for submission of online applications.
- While filling in the Application Form, the candidate should carefully decide about their choices for the centre for the examination.
- Candidates should note that no request for change of centre will be granted.
- Candidates in their own interest are advised to keep a copy of print out of filled form submitted online.

## Admit Card for Territorial Army Commission Exam

The admit card will be made available online for being downloaded by the candidate 10 to 15 days before the exam. The admit cards shall depict photograph and scanned signature of the candidate, date of birth, date / time of examination and also name and address of examination centre. In addition, admit & SMS alert will also be sent to the candidates for the same.

## Procedure for Selection

- Candidates whose application forms are found correct and are declared successful in the territorial army commission exam will be detailed for intelligence and personality test at the Service Selection Board.
- Two-stage selection procedure based on Psychological Aptitude Test and Intelligence Test has been introduced at Service Selection Boards. All the candidates will be put to stage one test on first day of reporting at Selection Centres. Only those candidates who qualify at stage one will be admitted to the second stage/remaining tests and all those who fail to pass stage one, will be returned.
- The male and female candidates will be placed in separate order of merit on the basis of the total marks secured by them in the territorial commission examination and in the SSB tests.

## Documents to be Produced at the Time of SSB Interview

Candidate who qualify in Territorial Army Commission exam (CBT) are required to bring the following documents in original along with self-attested copies at the time of SSB Interview:

1. Application Form IAF (TA)-9 (Revised) Part-2 to be downloaded from and fill it up in their own hand writing.
2. All educational qualification certificates (Matric onwards).
3. Copy of identity proof with photographs (Voter ID / PAN Card / Passport / Driving license / Aadhaar etc).
4. Proof of residential address.
5. Certificate for proof of age (Matric / Senior Secondary mark sheet and certificate for verification of date of birth).
6. Candidates whose names vary in documents should submit copy of Gazette notification of India / State showing the correct name or an affidavit duly supported by newspaper cuttings.
7. Latest income proof from the appropriate authority (i.e. Income Tax Revenue Department / Magistrate / Employer).
8. Copy of latest Income Tax Return (ITR) filed.
9. Admit Card.
10. Self-employed candidates are required to submit an Affidavit on Non-Judicial stamp paper of minimum value duly attested stating nature of employment and annual income along with photocopy of PAN card and self-certified character certificate.
11. Service certificate by candidates employed in Central Govt / Union Territory / State / Semi Govt / Private Sector Authenticated by Head Office along with salary certificate and No Objection Certificate by the department as per format given.

Candidates will be required to produce all certificates, in original, at the time of interview by Service Selection Board.

## Total Number of Vacancy

- (a) Male: 11
- (b) Female: 01

**Note:** The final selection shall be made in the order of merit up to the number of vacancies available. Vacancies are subject to change as per organizational requirement.

## Embodiment for Training

(1) Six months Pre-Commission Training as provided in **TA Rule 20A amended** vide SRO 65 dated 29 Apr 2023. **Candidates shall be commissioned in TA only on successful completion of the said Pre-Commission Training.** Post that the officer will be granted 21 days of Post commission leave.

(2) 30 days training under Rule-19 will be carried out after completion of post commission leave.

(3) Two months annual training camp every year. If not embodied for more than six months in a training year (April to March).

**Note:** Any attempt on the part of the candidate, directly or indirectly, to enlist support for their election or to suppress material information while filling the application form or otherwise, will disqualify them for the grant of commission.

## Terms and Conditions of Service

1. Territorial Army is part time concept with mandatory two months training in a year and does not provide full time career.
2. Serving in Territorial Army do not guarantee pension and the same is subject to embodied service as per organisational requirement.
3. Commission is granted in the rank of Lieutenant.
4. Pay and Allowances and privileges will be same as Regular Army Officers when embodied for training and military service.
5. Promotions upto Lt Col by time scale subject to fulfilling laid down criteria. Promotion to Colonel and Brigadier by selection.
6. Officers commissioned in Territorial Army may be called out for military service for longer duration depending on the requirement.

## Pay Scales (VIIth CPC)

| Rank | Level | Pay Matrix | Military Service Pay |
|------|-------|-----------|----------------------|
| Lieutenant | Level 10 | 56,100 – 1,77,500 | 15500/- |
| Captain | Level 10A | 61,300 – 1,93,900 | 15500/- |
| Major | Level 11 | 69,400 – 2,07,200 | 15500/- |
| Lt Colonel | Level 12A | 1,21,200 – 2,12,400 | 15500/- |
| Colonel | Level 13 | 1,30,600 – 2,15,900 | 15500/- |
| Brigadier | Level 13A | 1,39,600 – 2,17,600 | 15500/- |
MD,
            ],

            'officer-ex-service' => [
                'title' => 'For Ex-Service Officer',
                'menu_label' => 'For Ex-Service Officer',
                'seo_title' => 'Join as an Officer — For Ex-Service Officer — Territorial Army',
                'seo_description' => 'Eligibility and application process for Ex Armed Forces Commissioned Officers to join the Territorial Army.',
                'body' => <<<'MD'
Applications are invited from gainfully employed Ex Armed Forces Commissioned Officers for an opportunity of donning the uniform and serving the Nation as Territorial Army Officers, based on the concept of enabling motivated Ex Service Officers to serve in a military environment without having to sacrifice their primary professions. You can serve the Nation in two capacities — as a civilian and as a soldier.

## Total Number of Vacancy

- (a) Male: 04
- (b) Female: 01

**Note:** Total Vacancies are subject to change as per organizational requirement.

## Conditions of Eligibility

1. **Only Ex Service Commissioned Officer Can Apply**
2. **Nationality:** Only citizen of India (male and female).
3. **Age Limits:** 18 to 42 years on the date of application.
4. **Educational Qualifications:** Graduate from recognized university.
5. **Physical Standards:** A candidate must be physically and medically fit in all respects.
6. **Employment:** Gainfully Employed.

**Note:** Serving member of the Regular Army / Navy / Air Force / Police / GREF / Para Military and like forces are not eligible.

**Last Date of Receipt of Application:** Application form completed in all respects will be accepted from **27 July till 25 August 2026** at Directorate General Territorial Army (TA-4), Integrated Headquarters of Ministry of Defence, 4th Floor, 'A' Block, Ministry of Defence Office Complex, KG Marg, New Delhi – 110001. Call on Queries Related to: Territorial Army Officer: 011-21411121

**Type of Exam:** Only Interview. The eligible candidates shall be issued a call-up letter and will be sent by post by the Territorial Army Directorate.

**Date of Interview:** SEPTEMBER 2026 (Firm dates will be communicated later by post / e-mail). The date of holding the Interview as mentioned above is liable to be changed at the discretion of the competent authority. (Ex Service Officer should note that no request for change of date of Interview will be granted.)

## How to Apply

All desirous Ex Service Officers (Commissioned Officers only) of three services can download Application Form **IAF (TA)-9 (Revised) Part – 1** from www.jointerritorialarmy.gov.in (FREE OF COST) and send applications along with Photocopy of **Release Order** and Photocopy of **Release Medical Board Proceedings** to Directorate General Territorial Army, Integrated Headquarters of Ministry of Defence, 4th Floor, 'A' Block, Ministry of Defence Office Complex, KG Marg, New Delhi – 110001. **The application be sent by India Post only.**

## Selection Procedure

1. The Ex Service Officers will be screened by the Army Headquarters Selection Board (ASB) held at Additional Directorate General Territorial Army, Integrated Headquarters of Ministry of Defence, 4th Floor, 'A' Block, Ministry of Defence Office Complex, KG Marg, New Delhi – 110001.
2. Recommended Ex Service Officers will undergo Medical Examination at Armed Forces Clinic, New Delhi followed by their Police Verification for final selection. All such officers are given the credit of their previous commissioned service on joining the Territorial Army.

## Embodiment for Training

1. Pre-Commission Training as provided in **TA Rule 20A**. (Candidates shall be commissioned in TA only on successful completion of the said Pre-Commissioned Training).
2. Two months Annual Training Camp every year including the first year (as applicable).

## Terms and Conditions of Service

1. Territorial Army is part time concept with mandatory two months training in a year and does not provide full time career.
2. Service in Territorial Army do not guarantee pension and the same is subject to embodied service as per organisational requirement.
3. Commission is granted in the rank of Lieutenant, subject to meeting requirements laid down in TA Rule 20A.
4. Pay and Allowances and privileges will be same as Regular Army Officers when embodied for training and military service.
5. Promotions upto Lt Col by time scale subject to fulfilling laid down criteria. Promotion to Colonel and Brigadier by selection.
6. Officers commissioned in Infantry TA may be called out for military service for longer duration depending on the requirement.
7. Once previous service is verified from concerned PCDA(O) they are granted Ante-Date Seniority for Pay and Promotion.

## Pay Scales (VIIth CPC)

| Rank | Level | Pay Matrix | Military Service Pay |
|------|-------|-----------|----------------------|
| Lieutenant | Level 10 | 56,100 – 1,77,500 | 15500/- |
| Captain | Level 10A | 61,300 – 1,93,900 | 15500/- |
| Major | Level 11 | 69,400 – 2,07,200 | 15500/- |
| Lt Colonel | Level 12A | 1,21,200 – 2,12,400 | 15500/- |
| Colonel | Level 13 | 1,30,600 – 2,15,900 | 15500/- |
| Brigadier | Level 13A | 1,39,600 – 2,17,600 | 15500/- |

## Documents required at the time of interview

Ex Service Officers are required to bring the following documents in original along with attested copies at the time of interview. All those ex service officers whose complete documents are not produced for verifications will not be allowed to attend the interview.

1. Application Form IAF (TA)-9 (Revised) Part-2 to be downloaded and filled up in their own hand writing.
2. All educational qualification certificates (Matric onwards).
3. Latest physical fitness certificate from a registered MBBS Doctor.
4. Copy of Identity proof with photographs (Voter ID / PAN Card / Aadhaar Card / Passport / Driving license etc).
5. Domicile / Residential proof.
6. Certificate for proof of age (Matric / Senior Secondary mark sheet & certificate for verification of date of birth).
7. Service certificate by candidates employed in Central Govt / Union Territory / State / Semi Govt / PSU Authenticated by Head of office, along with No Objection Certificate by the department as per format given.
8. Service certificate by candidates employed in Private Sector Authenticated by Head of office along with No Objection Certificate by the department as per format given.
9. Self-employed candidates are required to submit an Affidavit on Non-Judicial stamp paper of minimum value duly attested stating nature of employment and annual income along with photocopy of PAN card and Self-certified Character Certificate.
10. Ex Service Officers whose names vary in documents should submit copy of Gazette notification of India / State showing the correct name duly supported by newspaper cuttings.
11. Copy of Release Order.
12. Copy of Release Medical Board Proceedings.
13. Latest income proof from the appropriate authority (i.e. Income Tax Revenue Department / Magistrate / Employer).
14. Copy of PAN Card.
15. Copy of Aadhar Card.
16. Copy of Certificates / Reports of all Courses.
17. Discharge / Service Particular Booklet.
18. Copy of latest Income Tax Return (ITR) filed.

## Applicable Certificates at Interview

1. CERTIFICATE TO BE RENDERED BY CANDIDATES EMPLOYED IN CENTRAL GOVT / UNION TERRITORY / STATE GOVT / SEMI GOVT DULY AUTHENTICATED BY HEAD OF OFFICE.
2. CERTIFICATE TO BE RENDERED BY CANDIDATES EMPLOYED IN PRIVATE SECTOR (TO BE AUTHENTICATED BY HEAD OF OFFICE).
3. SELF-CERTIFIED CHARACTER CERTIFICATE TO BE RENDERED BY SELF EMPLOYED PERSONNEL (SELF CERTIFIED BY THE CANDIDATE).
4. AN AFFIDAVIT TO BE RENDERED BY SELF EMPLOYED PERSONNEL ON NON-JUDICIAL STAMP PAPER OF MINIMUM VALUE DULY ENDORSED BY NOTARY.
MD,
            ],

            'officer-syllabus' => [
                'title' => 'Syllabus / Pattern of Online Entrance Exam',
                'menu_label' => 'Syllabus',
                'seo_title' => 'Syllabus — Online Entrance Exam — Territorial Army',
                'seo_description' => 'Exam pattern, duration, penalty for wrong answers and syllabus for the TA Commission online entrance test.',
                'body' => <<<'MD'
**Type of Exam:** Computer Based Test (CBT).

**Exam Duration:** Maximum time is 02 hours.

**Qualifying Marks:** The Territorial Army Directorate has discretion to fix qualifying marks in any or all the subjects of the examination.

**Penalty for Wrong Answers:** For each question for which a wrong answer has been given by the candidate, **one third (0.33%)** marks assigned to that question will be deducted as penalty. Question marked for review will be treated as unanswered.

**Objection Management:** Objection Management Link shall be hosted on DG TA website after 72 hours from the last date of examination inviting concerns of the candidates related to questions and answers keys of the examination, if any. The objection management link shall be shared with the candidates on their registered email for downloading of response sheet. Objection management link will be live for only three days.

## Pattern for Conduct of Territorial Army Entrance Exam

### Standard and Syllabus of the Examination

| Paper | Subject | Time Allotted | No. of Questions | Marks |
|-------|---------|---------------|------------------|-------|
| In one session | Part-1 Reasoning | 2 Hours | 40 | 40 |
| | Part-2 General Knowledge | | 30 | 30 |
| | Part-3 English | | 30 | 30 |
| | **Total** | | **100** | **100** |

**NOTE:** There will be **No Mathematics section** in the coming 12 July 2026 exam. Only Reasoning, General Knowledge and English section will be in the question paper.

**Standard:** The standard of papers will approximately be such as may be expected of a graduate of an Indian university.

## Syllabus

**Part – 1. Reasoning.** The question paper will be designed to test the candidates' ability to complete sequences making logical conclusion based on simple pattern of numbers, statements, figures, letters etc. as may be expected of a rational thinking person without any special study of the subject.

**Part – 2. General Knowledge.** General knowledge will consist of general knowledge & current affairs each having equal weightage. The paper will include questions on current affairs and such matters of everyday observation and experience in scientific aspects as may be expected of an educated person who has not made a special study of any scientific subject. The paper will also include questions on history of India and geography of nature which candidates should be able to answer without special study.

**Part – 3. English.** The question paper will be designed to test the candidates' understanding of English and workmanlike use of words.

## Candidates to Ensure Their Eligibility for the Examination

The candidates applying for the examination should ensure that they fulfil all the eligibility conditions for the commission. Their candidature at all the stages of the commissioning process will be purely provisional subject to satisfying the prescribed eligibility conditions. **Mere issue of Admit Card to the candidate will not imply that his candidature has been finally cleared by the competent authority.** Verification of eligibility conditions with reference to original documents will be taken up only after the candidate has qualified for the Service Selection Board interview.

**Use of Unfair Means.** Any candidate found using unfair means will be immediately disqualified and will have to give undertaking to this effect to the invigilator staff. If the candidate refuses to give undertaking, the invigilator staff should record under his signature.

**Mobile Phones Banned.**
(a) The use of any mobile phone (even in switched off mode), pager or any electronic equipment or programmable device or storage media like pen drive, smart watches etc. or camera or blue tooth devices or any other equipment or related accessories either in working or switched off mode capable of being used as a communication device during the examinations strictly prohibited. Any infringement of these instructions shall entail disciplinary action including ban from future examinations.

(b) Candidates are advised in their own interest not to bring any of the banned item including mobile phones or any valuable / costly items to the venue of the examination, as arrangement for safe-keeping cannot be assured. Commission will not be responsible for any loss in this regard.
MD,
            ],

            'medical-examination' => [
                'title' => 'Medical Examination Procedure',
                'menu_label' => 'Medical Examination',
                'seo_title' => 'Medical Examination Procedure — Territorial Army',
                'seo_description' => 'Medical examination procedure for recommended candidates at Selection Centres, Appeal Medical Board and Review Medical Board.',
                'body' => <<<'MD'
## Click below for

- GENERAL INSTRUCTIONS & MEDICAL STANDARD FOR TERRITORIAL ARMY COMMISSION (TAC) FOR CIVILIAN CANDIDATES
- MEDICAL STANDARDS AND PROCEDURE OF MEDICAL EXAMINATION FOR OFFICER ENTRY.

## Medical Examination Procedure of the Recommended Candidates at Selection Centres

### General

1. Recommended candidates appear before the medical board for carrying out their medical board after the SSB results. It takes 4 to 5 days time for the concerned Military Hospital to complete the medical board and after that the candidates are dispersed. President of the medical board will guide the candidates for Appeal / Review Medical Board procedures. Candidates may seek the advice of President Special Medical Board for review / appeal in case they have been declared unfit.

### Procedure of Appeal Medical Board

2. The Appeal Medical Board will be held at one of the following hospitals:

(a) Base Hospital, Delhi Cantt.
(b) Command Hospital, Southern Command, Pune.
(c) Command Hospital, Eastern Command, Kolkata.
(d) Command Hospital, Central Command, Lucknow.
(e) Command Hospital, Western Command, Chandimandir.
(f) Command Hospital, Air Force, Bangalore.
(g) Command Hospital, Northern Command, C/o 56 APO.
(h) INHS, Ashvini, Mumbai.

3. The candidates will report for medical examination within the stipulated period i.e., 42 days from the date last examined by the Special Medical Board for re-examination along with the receipted copy of the MRO / TR as directed by the President Medical Board. The Endeavour will be to complete the Medical Board in one day.

### Review Medical Board

4. In case of candidate being declared unfit by the Appeal Medical Board, he / she may challenge the proceedings and may be granted review of medical proceedings based on the merit of the case. Any candidate desiring for a review should address the request to Recruiting Directorate (Personnel & Coord), Army Headquarters, West Block III, RK Puram, New Delhi with a copy to the President of Appeal Medical Board within one day of the holding of Appeal Medical Board. The application for RMB are routed through DG AFMS. The decision for grant of RMB is with DG AFMS, and is not a matter of right. RMBs are conducted at R&R Hospital Delhi Cantt and AFMC, Pune.

### Stay During the Medical Examination

5. The recommended candidates after the declaration of SSB results will stay in the Selection Centres in the lines earmarked for the recommended candidates for their medical examination by the Military Hospital. The candidates will be allotted fresh medical chest numbers for the purpose.

6. Candidates who go for Appeal Medical Board or have to get their medical examination done at a later date due to some unavoidable reasons will do so under their own arrangements.

### Discipline during Medical Examination

7. It is utmost important that recommended candidates maintain proper discipline during their stay in Selection Centres during their conduct of medical examination. The candidates are required to put on their medical Chest Number (with Red Cross) whenever they are present in the campus of Selection Centres.
MD,
            ],

            'officer-training' => [
                'title' => 'Officer Training',
                'menu_label' => 'Training',
                'seo_title' => 'Officer Training — Territorial Army',
                'seo_description' => 'Embodiment for training, TA Rule 20A amended, and Ministry of Defence notifications governing TA training.',
                'body' => <<<'MD'
## Embodiment for Training

Six months Pre-Commission Training as provided in **TA Rule 20A amended** vide SRO 65 date 29 Apr 2023.

(Candidates shall be commissioned in TA only on successful completion of the said Pre-Commission Training).

---

### MINISTRY OF DEFENCE — NOTIFICATION

**New Delhi, the 29th April, 2023**

**S.R.O. 64.** — The Central Government hereby makes the following Regulations further to amend the Territorial Army Regulations, 1948, namely:

1. (1) These Regulations may be called the Territorial Army (Amendment) Regulations, 2023.
   (2) They shall come in force on the date of their publication in the Official Gazette.

2. Para 25 (a) of TA Regulations, 1948 (Revised Edition-1976):

**For:**
> 25. (a) Counting of Service for Increments of Pay — For purpose of increments of pay, officers will be permitted to count all commissioned service in the Territorial Army under Sec 7(3)(a)(b) & (c) of the Act.

**Read:**
> 25. (a) Counting of Service for Increments of Pay — For purpose of increments of pay, officers will be permitted to count all commissioned service in the Territorial Army under Sec 7(3)(a)(b) & (c) of the Act. **The training period during the Pre-Commissioning Training, however, shall not be counted for the said purpose in terms of the training, as given in Sec 7(3).**

---

### NOTIFICATION

**New Delhi, the 29th April, 2023**

**S.R.O. 65.** — In exercise of the powers conferred by section 14 of the Territorial Army Act, 1948, the Central Government hereby makes the following rules further to amend the Territorial Army Rules, 1948, namely:

1. (1) These rules may be called the Territorial Army (Amendment) Rules, 2023.
(2) They shall come in force on the date of their publication in the Official Gazette.
2. In the Territorial Army Rules, 1948 (herein after referred to as the said rules), for rule 18, the following rule shall be substituted, namely:

> "18. Military training — Military training for Territorial Army unit shall consist of the following namely:
> (a) Recruit Training;
> (b) Annual Training;
> (c) Pre-Commission Training;
> (d) Post-Commission Training;
> (e) Voluntary Training; and
> (f) Service on the permanent staff.
>
> Provided that Post-Commission Training shall be given only for Departmental Territorial Army officers."

3. For rule 20A of the said rules, the following rule shall be substituted, namely:

> "20A. Pre-Commission Training for candidates appointed to non-Departmental Territorial Army units:
>
> (1) Every person appointed for Territorial Army commission shall undergo Pre-Commission Training, under the orders of the Director General or Additional Director General of Territorial Army for a total period not exceeding twenty-three weeks, in a training institute as may be specified by the Ministry of Defence for this purpose.
>
> (2) A candidate shall be commissioned in the Territorial Army only on successful completion of the Pre Commission training referred to in sub-rule (1).
>
> (3) During the Pre-Commission Training, a person shall be treated at par with a gentleman or gentlewoman cadet, as the case may be, of the training institute where such person undergoes training, for all purposes including post commissioning leave for a duration of twenty-one days;
>
> Provided that Ex-officers commissioned in Territorial Army through the Army Headquarters Selection Board (ASB), may be exempted from undergoing Pre-Commission Training, if, in the opinion of the Director General or Additional Director General of Territorial Army, such officers have undergone adequate Military Training in their previous service in the Armed Forces."

4. In rule 22 of the said rules, for the words, figures and letter "rule 20, rule 20-A", the word and figures "rule 20" shall be substituted.

5. In rule 23 of the said rules, in clause (a), for the words "Every person subject to the Act, shall", the words "Every person subject to the Act, other than the persons undergoing Pre-Commission Training, shall" shall be substituted.
MD,
            ],

            'ssb-selection-center-information' => [
                'title' => 'Selection Center Information',
                'menu_label' => 'Selection Center Information',
                'seo_title' => 'SSB Selection Centre Information — Territorial Army',
                'seo_description' => 'History and information on SSB selection centres at Allahabad, Bhopal, Bangalore and Jalandhar.',
                'body' => <<<'MD'
## History of Selection Centre East, Allahabad

Selection Centre East, Allahabad was raised in 1957 with a view to recommend suitable candidates based on proven concept via Psychological Tests, Group Discussions, Ground Tasks and Personal Interview. It has five Services Selection Boards namely 11 SSB, 14 SSB, 18 SSB, 19 SSB and 34 SSB (Air Force).

- Telephone: 0532-2544001 / 0532-2544076
- Email: ssboiallahabad@gmail.com

## History of Selection Centre Central, Bhopal

Selection Centre Bhopal was established in October 1979 by relocating 20 SSB & 21 SSB which was earlier located at Jabalpur and was raised to 19, 14 ex 19SSB & 81 (Lt. PBR) respectively. Subsequently 22 SSB which was first raised in Dehradun in May 1962, and subsequently re-raised at Roorkee in Apr 1971 moved to Bhopal in Nov 1976. The Central Command till recently had three Boards 20 (Y) Jan 2003 when 4 new boards, 17 SSB were raised.

Selection Centre Central, Bhopal comprises of 20, 21, 22 & 33 (Naval Services Selection Boards). It is headed by an officer of the rank of Major General who is the Commandant of the Centre as also the President of 20 SSB. The boards are picked with interviewing officers, Group Testing Officers, Technical Officers and civilian Psychologists.

## History of Selection Centre South, Bangalore

Selection Centre South is located in the city of Bangalore. Initially SC South was located at Bhopal's Satellite Centre in close proximity to both Bhopal Selection Centre as well as Raja Dhar Airstrip. The Centre is housed in the new RLY accommodation, and plans are afoot to shift SSB operations to a newly developed design of the main building. The Selection Centre today boasts of a completely new infrastructure with modern facilities for the conduct of testing, administration, dining and recreational facilities for the candidates.

- Telephone: 0755-2782981
- Email: sscsouth@nic.in

## History of Selection Centre North, Jalandhar

Selection Centre North was raised on 01 Jul 2015 to cater for induction of female candidates in the armed forces, but for the benefit of female candidates only, as other select Boards are closed to them.

The motto of the centre is "SEVAPARAH, NASAMPATTAI NISAPADITAH".

More information at **www.sscnorth.gov.in**.

---

## The Selection Process

The procedure is based on selected personality traits and qualities designated as officer-like qualities (OLQs). These have evolved over a period of time.

Prior to Second World War, Selection of officers was mostly by written exams and interview boards. During the war, written tests were dropped and only interview by the then Central Interview Board was carried out.

The Brief history of evolution of the selection process is as follows:

**Year 1943:** Experimental board set up in Dehradun on the lines of the Office Selection Boards in the UK. This worked alongside the Central Interview Board and administered intelligence, aptitude, personality tests & practical group standards and worked for better assessments.

**Year 1946:** Ghosh Committee was appointed to study validity of selection system. It recommended continuation with few improvements with introduction of a research organization. This gave birth to the Psychological Research Wing (PRW). PRW later developed into the Directorate of Psychological Research (DIPR).

**Year 1950:** Officer quality rating scale was developed by the PRW. Options obtained from 102 officers, 38 members of selection boards & members of PRW were analysed, yielding 141 categories, thereafter condensed into 29 qualities.

**Year 1986:** Based on more studies, OLQ further condensed to 15 qualities. They have now been grouped into four factors depending upon their inter-correlations.

---

## The Philosophy of Assessment

Manners, snacks, tantrums are three Sanskrit words. The word manners refers to the initial, swarthies retire in speech, and tantrums refers to actions. These three words are together used to describe a sort of consistency expected of an individual. Manners, read as 'tantrums' is usually mistaken to imply that one should strive to achieve the state where ones' thoughts, speech and the actions coincide.

It is in accordance with this principle that three different concepts using three different techniques, namely the Psychology (manners), Interview (snacks), and GTO (tantrums) assess candidates at their qualities. The assessor gauge a candidate's present level and thereafter give him a predictive level which he is likely to attain on completion of military training. Those who come up to the required standard based on a rating scale are recommended.

---

## Reporting of Candidates

The routine in the Selection Centre is arrays in the sense that the SSBs function without any weekend breaks or holidays in-between batches. A selection board will typically function for about twenty-five days i.e., five batches in a month. Thus, the break is arranged to flush out the Board break between each board and the next twenty-five days. Service officers carry out assessment in neat clothing range for the conference top when uniform is worn.

---

## Reception and Reception

The routine in the Selection Centre is arranged to the extent that the SSBs function without any weekend breaks or holidays in-between batches. A Selection board will typically function for about twenty-five days i.e., five batches in a month, through DAF. The batches are placed based on certain types of applications received from the Recruiting Directorate as also directly through online applications in current cases of technical nature. The meticulous planning of batches and the calling up of individual candidates is a sophisticated and involves a critical effort by a team of dedicated staff of the Call-Up Office at the Selection Centre.

---

## The General Routine

The routine in the Selection Centre is arranged to the extent that the SSBs function without any weekend breaks or holidays in-between batches. A selection board will typically function for about twenty-five days i.e., five batches in a month. This break is arranged to flush out between Monday through Sunday, and holidays are adjusted. Service officers carry our assessment in neat clothing range for the conference top when uniform is worn.

---

## The Schedule of Events

The reporting and dispatch cycle works continuously. The entry due which carries candidates back to the railway station our completion of a batch also receives and brings back a fresh batch. Arriving candidates are directed to report to the MCO office at the railway station by mid noon.

---

## The Arrival

On arrival candidates are provided with refreshments, allotted chest numbers and briefed in detail. This is followed by basic documentation.

---

## The Assessment

### Stage 1: Screening Test (Intelligence Test)

Large number of candidates report for selection in SSB. All may not have the desirable qualities to be selected for armed forces so there is no point to go through the stage-II tests. Hence, the need to filter out these candidates on the very first day so that they can return to their home stations without wasting time.

Candidates are put through a screening process which is also known as the Stage I Tests. This practice has been introduced due to substantial increase in number of candidates reporting for SSB. The Stage I Tests consists of intelligence or logic test, a picture perception test and an individual narration cum group discussion test. The test on first screening ensure that only the candidates get selected for the Intelligence Test and only those are given a chance to project their capabilities. These Stage I Tests are commonly conducted for Service candidates.

**(Stage – 1)** The stage one has two distinct parts:

- Intelligence Test. This is a simple test of logic / analytical aptitude based on which an intelligence rating ranging from I to V is awarded to the candidates which is based on the number of total correct answers. It is but obvious that the candidate with a higher intelligence rating will benefit in the entire process of testing including stage I.
- Picture Perception and Discussion Test. You never shown a picture on the screen which you were required to perceive correctly. Then was thereafter given 1 minute to note down the characters, their age, their mood, etc as it was there. This was followed by individual story telling and discussion test. You answer who performed well on the stage I test, were awarded other Stage I. The remaining stage I of other stage I tests are advised to make the complete the tests. Large group is then divided into small groups of 10 to 12 candidates each and the candidates in the group discuss the perceived story and narrate one important facets of their test. Your body language and attitude during the whole test is also of consequence. What you must do is to remain involved with the test throughout.

Candidates successful in Stage I are retained for conduct of subsequent tests. Rejected candidates are returned, the next day of reporting to the Selection Centre.

**(Stage – 2)** This stage does not consist of any of the tests listed above. The stage-II tests are extensive, continuous and comprehensive of the Psychological tests, Group tasks and personal interview. This will be assessed separately. However, it is emphasised that all the three techniques involved in the assessment is of your personality which is based on specific OLQs (Officer Like Qualities). The highlights of the three different techniques are as follows:

### Stage – II Psychological Assessment

The SSB schedule commences with the administration of the Psychological tests. Candidates are put through aquative reaction, thematic perception, word association and self-description tests which lays the foundation for your psychological assessment by the Psych.

The stage-II tests comprise of three different techniques, namely, the psychological tests, group tasks and personal interview. This shall be understood separately. However, it is emphasised that all the three techniques of the assessment is of your personality which is based on specified fifteen OLQs (Officer Like Qualities). The highlights of the three different techniques are as follows:

The Psychological Test: The Psychological tests are timed with you now, to know you by your physical appearance or attributes. He will assess your personality merely through the inputs which you have given him in the answer sheet. It is a test of your ability. Your answers are required to be natural responses and not pre-structured. In case you have tried to think and modify your response natural to the mode to complete the test. GKs do STATs and the ARs). Any candidate can achieve proficiency in completing these tests through adequate practice. Various books are available on the market about the explains its tests and their standards of your help you. In addition, lot of coaching are also available, who train the cadets in the respective fields. One important feature on the mark of personality the person will constantly complete the tests in the given time and in the best possible fashion of all time response should be practical.

### GTO Test, Group Assessment

The GTO test series are conducted over a period of two days. The tests include group tasks, command tasks, lectures, group discussions, group planning exercise and other outdoor exercises.

Unlike the psychologists, the GTO is actually interacting with you throughout the tests. Each and every action of yours is observed. It is a test of your body and coordinated behavior among your body and mind. Your physical and mental qualities get highlighted in these tests. Project your qualities to the GTO and do not remain a passenger. Come out with work-ethic, ideas and ensure that others accept and their ideas when acceptable. While you support others to accept your ideas you should be willing to accept others' ideas as well. You need to remain confident and cheerful throughout the test and that is competed of instructive and ensure that no rules are violated. Your overall performance in all the GTO series brings out your personality. You need to be good in every GTO test. Correcting performance in a chair or two in insufficient. You will be observed along with your team and keep playing to your beat. You must be in-charge of the team. Judges are advised to remain attentive while explaining a task to the whole team.

### Personal Interview

Each Candidate faces one interview, which lasts for approximately four hours. The interview is conducted by any one of the three days. It is a test of your knowledge, intelligence, social skills and overall social capabilities of life. The interviewing officer is fairly senior and experienced in the armed forces. While he interacts with you he evaluates whether you have the essential qualities to be trained to be an army officer. While you have done some your activities with regard to your home educational performance of exams from 10th onwards, your achievements in extra curricular activities, your hobbies, sports, activities and any other interests in life, your interactions during the CTO tests, your general knowledge and general awareness of current events in the past etc. It is recommended, the interviewing officer understands your views and expectations as well as your family background, especially gives you from his interactions with you during group and mental interaction gets a glimpse of what your personality is all about. You has those committed. You do not try to hide anything. It the knowledge of lying all stating to false scientific attention. Be natural and remain open-minded.

### Conference — The Final Frontier

The final decision regarding selection of the candidate is made during the conference. The conference is carried out on the fifth day. The candidate having been tested by individual assessors is now evaluated as a whole in the common conference hall by the entire board of assessors. The final word is then made whether a candidate has been judged suitable and recommended or not. As simple as that.

### Facilities for the Candidates

For most of the candidates, the SSB is the first contact with the armed forces and life in the uniform. It is therefore the endeavour of the Services Centre to have a comfortable, interesting and a memorable life once experience. Towards this many facilities have been made available for the convenience and recreation of candidates. Apart from keeping them engaged, it also helps in de-stressing.

### Accommodation

The candidates on reporting at the SSB are allotted comfortable accommodation and messing. The Mess has the capacity to feed 150 candidates sitting at any one time. They are authorised full subsidized rates of messing. The core tenure has been designed for the candidates with minimum of any single/double occupancy with an attached bathroom.

### Billets

The candidates are accommodated in two-member rooms which are proximate to the SSB testing area. The building has single storied building with modern facility. The building has exceeded 5 fully acceptable Billets offices. In case of any emergency like during cleanup or other maintenance, the candidate can be posted to two or three times depending on availability of rooms. However, the full occupancy is 160.

### Staying Connected

The candidates on their arrival are provided with Internet, printing and photocopy facilities to enable them to stay updated with the connected to the outside world.

### Motivating Mindset

The motivation hub of the Selection Centre is very informative and especially designed to inspire candidates. Successful displays pertaining to the various Service Branches. Candidates can find a glimpse of the position of the Nation. Many a times, the serving officers interact and guide the candidates in the country out of a motivation they would be attempting.

MD,
            ],

            'ssb-interview-instructions' => [
                'title' => 'Instruction For SSB Interview',
                'menu_label' => 'Instruction For SSB Interview',
                'seo_title' => 'Instructions for SSB Interview — Territorial Army',
                'seo_description' => 'Reporting, documents, dos & don\'ts for candidates attending the Service Selection Board (SSB) interview.',
                'body' => <<<'MD'
Candidates reporting for SSB interview should follow these instructions carefully.

- **Report on date.** Report at the designated Selection Centre on the date and time printed on your call-letter. Late reporting is at your own risk and may lead to not being tested.
- **Documents to carry.** Original + self-attested copies of: 10th and 12th mark sheets, graduation degree, caste / category certificate (if applicable), domicile, Aadhaar, PAN, latest photographs (24+), Admit Card / call-letter, employment certificate where applicable, Income Tax Return and proof of income, and the filled-in PIQ (Personal Information Questionnaire) form.
- **Kit list.** Plain white shirt, plain white vest, light-coloured trousers, PT kit (shorts + T-shirt + sports shoes), formal shoes, shaving kit, slippers, towel, undergarments for 5 days, pen, pencil, eraser, notebook. Keep baggage minimum.
- **Dress and grooming.** Formal attire for interview (shirt + trouser + tie for men; salwar-kameez / formal pant-shirt for women). Hair combed, clean shaven or neatly trimmed beard where religion requires, shoes polished.
- **Mobile phones.** Banned inside the testing area. Switch off and deposit where instructed. Any attempt to carry or use will lead to immediate disqualification.
- **Discipline.** Address officers as "Sir / Ma'am". Follow fall-in timings. Attendance at all tests and briefings is compulsory.
- **During tests.** Speak clearly, do not interrupt others, back your opinions with reasoning, be open to others' views. In GTO tasks lead with intent but remain a team player.
- **Medical.** Recommended candidates will be put through a medical board. Arrive rested and well-hydrated. Declare any pre-existing conditions truthfully.
- **Travel reimbursement.** First-time SC class train fare is reimbursed to candidates by shortest route — carry original ticket / proof of travel.
- **Help desk.** For queries at the Selection Centre, visit the Call-Up Office or the Administrative Office of the Board hosting you.

Candidates are advised to be honest in the Personal Information Questionnaire and in the interview. Fabrication is noticed quickly by trained assessors and results in rejection regardless of other performance.
MD,
            ],

            'ecological-ta' => [
                'title' => 'Ecological Territorial Army',
                'menu_label' => 'Ecological TA',
                'seo_title' => 'Ecological Territorial Army — Afforestation and ecology units',
                'seo_description' => 'Ten TA battalions sponsored by state governments, using State Forest Department expertise for afforestation, soil conservation and ecological restoration.',
                'body' => <<<'MD'
## Ecological TA

There are ten ecological TA battalions which are sponsored by various state governments. TA Battalions use technical expertise of the respective State Forest Department (SFD), for afforestation related activities, Soil Conservation to promote the environment.

### BACKGROUND

The early eighties were a trying and testing time for the fragile ecology of the Shivalik Ranges. The Queen of Hills — Mussoorie — was on the brink of losing its pristine splendour, due to the unscientific and illegal mining of limestone. The unorganised mining activity in the area, intensified the rate of deforestation in an unprecedented manner.

Alarmed at the ecological degradation in the Shivaliks, Dr Norman Borlough, from Wheat and Maize Centre, Mexico suggested to the former Prime Minister of India, Late Mrs. Indira Gandhi to involve the Indian Army to restore the ecological balance on a war footing. As the regular Army could not be utilised for the purpose, it was decided that a Territorial Army Unit be raised for the purpose, enrolling ex-servicemen from the region.

This was with the dual aim of re-settlement of Ex-Servicemen and regeneration of ecology. As an unique experiment **127 Infantry Battalion (Territorial Army) GARHWAL RIFLES** was raised on **01 Dec 1982**.

### CONCEPT

Ecological Task Force (ETF) Battalions (TA) have been raised to execute specific ecology related projects, with military like work culture and commitment, by enrolling Ex-Servicemen. ETF Battalions (TA) use technical expertise of the respective State Forest Department (SFD), for afforestation related activities and promote environment.

### PRESENT STATUS

Presently nine such Infantry Battalions (TA) Ecological, comprising **22 operational companies**, are carrying out afforestation in difficult and degraded areas in States of Uttarakhand, Rajasthan, J&K, Himachal Pradesh, Delhi, Assam and Maharashtra. The Ecological Bn for Maharashtra has been raised in 2017 for drought-hit Marathwada region of the state. Also, a Composite Ecological Task Force for National Mission for Clean Ganga is under raising at Allahabad. On completion of raising, one company each will be deployed at Kanpur, Varanasi and Allahabad for the said mission. ETF Bns are either funded by Ministry of Environment, Forest and Climate Change (MoEF&CC) or by respective State Governments. These ETF Battalions have been raised with the twin objectives of restoration of fragile ecosystem and affording excellent opportunity for Army Veterans to contribute in Nation building.

### ACHIEVEMENTS

Till date ETF Battalions have planted approximately **6.88 crore saplings** and covered an area of **72,741 Hectares** of land, with **75 to 80 percent survival rate**. ETF Battalions have been given due recognition for their exemplary contribution in the field of ecology and have been bestowed with various National and State level awards.

### Initiative to Check Economic Migration along Border Areas

A unique initiative and vision of Indian Army has entailed plantation of high quality **Walnut and Chilgoza saplings in Malari region of Uttarakhand** to prevent migration of villagers. Malari region is strategically located in Niti Valley, on an axis leading to the China Border and is remotely located and cut off from Joshimath due to heavy snowfall between December – April. The locals migrate to lower regions during this period. There are no avenues of employment and even the cultivable land is inadequate for a sustainable livelihood. The project has been initiated on the directions of **General Bipin Rawat, UYSM, AVSM, YSM, SM, VSM, ADC, Chief of the Army Staff**, who conceived the idea and is the driving force behind the initiative.

The uniqueness of the initiative is that it involves all resident families in planting saplings of Walnut and Chilgoza, which will belong to them, and they would be sole beneficiaries of the fruits borne by the saplings planted by them.

The first phase of this pilot project was inaugurated at **Malari on 19 May 2017**. A large number of enthusiastic villagers attended the event and **4,000 saplings** of Walnut and Chilgoza were planted. In Phase II approximate **1,00,000 saplings** will be planted over the next three years.

**127 Inf Bn (TA) Eco, GARH RIF** and **130 Inf Bn (TA) Eco, KUMAON** have been earmarked for this project. The units have already prepared nurseries and sowed more than one lakh seedlings of Walnut specially procured from Central Institute for Temperate Horticulture, Srinagar, J&K. These saplings thereafter will be transplanted in designated areas of Garhwal and Kumaon region.

### CONCLUSION

By virtue of their task and role, the Ecological Territorial Army units are contributing immensely by ecological activities towards disaster mitigation and also be part of Nation building efforts of the Indian Army.
MD,
            ],

            'departmental-ta' => [
                'title' => 'Departmental Territorial Army',
                'menu_label' => 'Departmental TA',
                'seo_title' => 'Departmental Territorial Army — Oil, Railways and ONGC units',
                'seo_description' => 'Oil Sector (TA), Railway Engineer Regiments (TA) and ONGC (TA) units — how they are raised, eligibility, application process.',
                'body' => <<<'MD'
Departmental Territorial Army units are embodied during national emergency and natural calamities, and perform the same task they do in their parent department (IOC / Indian Railways / ONGC).

- Oil and Natural Gas Corporation (ONGC)
- Indian Railways
- Indian Oil Corporation (IOC)

## OIL SECTOR (TA) UNITS

The Assam agitation of 1980 resulted in colossal loss of oil production estimated at over **₹5,000 crores**. Combat Engineer Regiments were deployed to take over the Oil and Gas installations to maintain production. Raising of the Oil Sector (TA) units was the result of the lessons learnt during this Oil Operation. Accordingly, a proposal was initiated by the then Ministry of Chemical & Fertilizer (now Ministry of Petroleum and Natural Gas) for raising of Territorial Army Battalions, separately for all the three fields of Oil industry — Exploration, Refining and Marketing. The proposal was accepted and the raising of the following three Oil Sector (TA) units were approved by the Ministry of Defence:

- **801 Engineer Regt (TA)** — Refinery & Pipeline
- **811 Engineer Regiment Oil Natural Gas Corporation (TA)**
- **414 ASC Battalion Marketing (TA)**

These units have Territorial Army personnel enrolled from various fields of Oil Sector PSUs. During national emergency / crises these Territorial Army personnel are embodied, in which they are liable to perform the assigned duties. The task of Oil Sector (TA) units is more of technical in nature than of combat. The TA personnel of these units perform the same task which they do in their parent department i.e. in Oil Companies.

Women employees of Oil Sector PSUs are also allowed to be commissioned / enrolled as Officer, Junior Commissioned Officer and Other Ranks in Oil Sector (TA) units as commensurate to civil ranks. **Lt Shilpy Gargmukh**, an employee of ONGC, is the first lady officer of Oil Sector (PSU) who has been commissioned in 811 Engineer Regiment (TA) ONGC.

## RAILWAY ENGINEER REGIMENTS (TA)

There are six Railway Engineer Regiments (TA):

- 969 Railway Engineer Regiment (TA)
- 970 Railway Engineer Regiment (TA)
- 1031 Railway Engineer Regiment (TA)
- 1032 Railway Engineer Regiment (TA)
- 1101 Railway Engineer Regiment (TA)
- 1105 Railway Engineer Regiment (TA)

These units have Territorial Army personnel enrolled from various Railway Zones. During national emergency / crises these Territorial Army personnel are embodied, in which they are liable to perform the assigned duties. The TA personnel of these units perform the same task which they do in their parent department i.e. in Railways.

Women employees of Railways are also allowed to be commissioned / enrolled as Officer, Junior Commissioned Officer and Other Ranks in Railway Engineer Regiments (TA). Presently there are **five women sappers** enrolled with Railway Engineer Regiments (TA).

---

## How to Join Departmental Territorial Army

### 1. Conditions of Eligibility

- **Age Limits:** 18 to 42 years other than medical officer. 18 to 45 years of age for medical officer.
- **Educational Qualifications:** Graduation and appropriate technical qualification.
- **Employment:** Employment in Indian Railway / IOCL / HPCL / BPCL / ONGC.

### 2. Process

- Applicants to submit application through their HoD to TA Group HQs.
- If found suitable by a Board of Officers, will further undergo Medical Board at nearest Military Hospital.
- Process through TA Directorate and Ministry of Defence for final selection.
MD,
            ],

            'ta-policies' => [
                'title' => 'TA Policies',
                'menu_label' => 'TA Policies',
                'seo_title' => 'TA Policies — Territorial Army',
                'seo_description' => 'Official Territorial Army policy documents issued by Ministry of Defence.',
                'body' => <<<'MD'
The official **TA Policy** document issued by the Ministry of Defence is available below. Open or download for the full text.

> The PDF link is attached to this page from the Admin Panel → Pages → TA Policies → "Attached PDF". Admins can swap it when a newer policy is published.
MD,
            ],

            'ssb-5-days-video' => [
                'title' => 'SSB 5 Days Video',
                'menu_label' => 'SSB 5 Days Video',
                'seo_title' => 'SSB 5 Days Procedure — Video Walkthrough — Territorial Army',
                'seo_description' => 'Day-by-day video walkthrough of the five-day Service Selection Board procedure.',
                'body' => <<<'MD'
Walkthrough of what a candidate experiences across the five days at a Service Selection Board (SSB).

## Day 1 — Screening

Reporting at the Selection Centre, documentation, PIQ submission, intelligence test (OIR), picture perception and discussion test (PP&DT). Candidates who do not clear Day 1 are sent back the same evening.

## Day 2 — Psychological testing

Thematic Apperception Test (TAT), Word Association Test (WAT), Situation Reaction Test (SRT) and Self-Description Test (SD). Timed, pen-and-paper — your natural responses are what the psychologist assesses.

## Day 3 — GTO series (Part 1)

Group Discussion (GD), Group Planning Exercise (GPE), Progressive Group Task (PGT), Half Group Task (HGT) and Group Obstacle Race ("snake race"). Group of 8–10 candidates work together under the Group Testing Officer.

## Day 4 — GTO series (Part 2) + Interview

Individual Obstacle (IO), Command Task, Final Group Task (FGT) and Lecturette. In parallel each candidate is called for the Personal Interview with the Interviewing Officer — roughly 30–60 minutes covering your background, career, family, current affairs and views.

## Day 5 — Conference & result

Each candidate appears briefly before the full Board — the three assessors (IO, Psych, GTO) and the President. Recommended candidates stay back for the medical board; the rest are given a thorough closure briefing and dispersed.

The full video walkthrough will be embedded here. For now, the official DGTA YouTube channel has day-wise explainer videos.
MD,
            ],
        ];
    }
}
