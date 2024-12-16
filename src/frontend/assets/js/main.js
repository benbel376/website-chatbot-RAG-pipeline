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
import { SummaryLoader } from './modules/summaryLoader.js';
import { ExperienceLoader } from './modules/experienceLoader.js';
import { EducationLoader } from './modules/educationLoader.js';


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

    // Initialize summary loader with relative paths
    const summaryLoader = new SummaryLoader({
        basePath: './content', // Relative to the root of the frontend folder
        imagesPath: './assets/images'
    });

    // Load summary content
    const summaryContent = document.querySelector('#summary-content');
    if (summaryContent) {
        summaryLoader.renderSummary(summaryContent);
    }

    // Initialize experience loader
    const experienceLoader = new ExperienceLoader({
        basePath: './content'
    });

    // Load experience content
    const experienceContent = document.querySelector('#experience-content');
    if (experienceContent) {
        experienceLoader.renderExperience(experienceContent);
    }

    // Initialize education loader
    const educationLoader = new EducationLoader({
        basePath: './content'
    });

    // Load education content
    const educationContent = document.querySelector('#education-content');
    if (educationContent) {
        educationLoader.renderEducation(educationContent);
    }
});