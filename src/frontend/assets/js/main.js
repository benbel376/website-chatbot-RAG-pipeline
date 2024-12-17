'use strict';
console.log('Main.js loaded');

import { initSlideshow } from './modules/slideshow.js';
import { initPagination } from './modules/pagination.js';
import { initializePortfolio } from './modules/portfolio.js';
import { initializeBlogs } from './modules/blogs.js';
import { initContact } from './modules/contact.js';
import { initNavigation } from './modules/navigation.js';
import { initTestimonials } from './modules/testimonials.js';
import { initCodeLineAdder } from './modules/codeLineAdder.js';
import { initToolsTable } from './modules/toolsTable.js';
import { initCollapsibleSections } from './modules/collapsibleSections.js';
import { initChat } from './modules/chat.js';
import { initInfiniteScroll } from './modules/infiniteScroll.js';
import { initCollapse } from './modules/collapse.js';
import FloatingChat from './modules/floatingChat.js';
import initCertificationSlider from './modules/certificationSlider.js';
import { initWelcomeOverlay } from './modules/welcomeOverlay.js';


document.addEventListener('DOMContentLoaded', function() {
    initSlideshow(document.querySelector('.slideshow-outer-container'));
    initPagination(document.querySelector('.blog-details-container'));
    initNavigation();
    initializePortfolio('portfolio-container');
    initializeBlogs('blog-container');
    initContact();
    initTestimonials();
    initCodeLineAdder();
    initToolsTable();
    initCollapsibleSections();
    initChat();
    initInfiniteScroll();
    initCollapse();
    FloatingChat.init();
    initCertificationSlider();
    initWelcomeOverlay();

    // // Initialize summary loader with relative paths
    // const summaryLoader = new SummaryLoader({
    //     basePath: './content', // Relative to the root of the frontend folder
    //     imagesPath: './assets/images'
    // });

    // // Load summary content
    // const summaryContent = document.querySelector('#summary-content');
    // if (summaryContent) {
    //     summaryLoader.renderSummary(summaryContent);
    // }

    // // Initialize experience loader
    // const experienceLoader = new ExperienceLoader({
    //     basePath: './content'
    // });

    // // Load experience content
    // const experienceContent = document.querySelector('#experience-content');
    // if (experienceContent) {
    //     experienceLoader.renderExperience(experienceContent);
    // }

    // // Initialize education loader
    // const educationLoader = new EducationLoader({
    //     basePath: './content'
    // });

    // // Load education content
    // const educationContent = document.querySelector('#education-content');
    // if (educationContent) {
    //     educationLoader.renderEducation(educationContent);
    // }

    // // Initialize and load skills content first
    // const skillsLoader = new SkillsLoader({
    //     basePath: './content'
    // });

    // const skillsContent = document.querySelector('#skills-content');
    // if (skillsContent) {
    //     // Load skills content first, then initialize toolsTable
    //     skillsLoader.renderSkills(skillsContent)
    //         .then(() => {
    //             initToolsTable();
    //         })
    //         .catch(error => {
    //             console.error('Error loading skills:', error);
    //         });
    // }

    // // Initialize certifications loader
    // const certificationsLoader = new CertificationsLoader({
    //     basePath: './content',
    //     imagesPath: './assets/images'
    // });

    // // Load certifications content
    // const certificationsContent = document.querySelector('#certifications-content');
    // if (certificationsContent) {
    //     certificationsLoader.renderCertifications(certificationsContent);
    // }

    // // Initialize testimonials loader
    // const testimonialsLoader = new TestimonialsLoader({
    //     basePath: './content',
    //     imagesPath: './assets/images'
    // });

    // // Load testimonials content
    // const testimonialsContent = document.querySelector('#testimonials-content');
    // if (testimonialsContent) {
    //     testimonialsLoader.renderTestimonials(testimonialsContent);
    // }

    // // Initialize hero loader
    // const heroLoader = new HeroLoader({
    //     basePath: './content',
    //     imagesPath: './assets/images'
    // });

    // // Load hero content into the existing hero-wrapper
    // const heroContent = document.querySelector('.hero-wrapper');
    // if (heroContent) {
    //     heroLoader.renderHero(heroContent);
    // }
});