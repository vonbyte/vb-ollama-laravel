# Epic 1: Core Functionality

## Story 1: Prompt Submission & Model Selection
**As a user, I want to enter a text prompt and select models to compare their outputs.**

### Acceptance Criteria:
- [x] The prompt input field accepts a minimum of 2000 characters
- [x] Character count is displayed and updates in real-time
- [x] Empty prompt submission is prevented with a validation message
- [x] Model selection uses checkboxes for intuitive multi-select
- [x] Maximum of 4 models can be selected at once
- [x] Attempting to select more than 4 models shows an error message
- [x] Model list includes name and size information
- [x] A "Compare" button submits the form

## Story 2: Basic Output Comparison
**As a user, I want to see model outputs displayed with basic performance metrics.**

### Acceptance Criteria:
- [x] Outputs display in a clear, readable format
- [x] Multiple outputs are shown side-by-side on desktop and stacked on mobile
- [x] Each output card displays the model name
- [x] Cards include the response time in seconds
- [x] Text preserves all formatting from the model response
- [x] Results appear after submission without page reload
- [x] A loading indicator shows during generation

## Story 3: Model Management
**As a user, I want to see a simple list of my available models.**

### Acceptance Criteria:
- [x] All locally installed models are listed on the comparison page
- [x] Each model entry shows name and size
- [x] A refresh button updates the model list
- [x] Error message appears if Ollama service is unavailable

# Epic 2: Enhancements (Post-MVP)

## Story 4: History and Storage
**As a user, I want to save my previous comparisons for future reference.**

### Acceptance Criteria:
- [ ] Basic history functionality stores recent comparisons
- [ ] History page shows previously run comparisons
- [ ] Each history entry shows the prompt and models used
- [ ] History persists across browser sessions using local storage

## Story 5: Basic Analysis Tools
**As a user, I want to analyze basic model performance.**

### Acceptance Criteria:
- [ ] Response times are displayed prominently
- [ ] Simple export functionality for comparison results
- [ ] Basic notes can be added to comparisons

## Story 6: Additional User Experience Improvements
**As a user, I want an intuitive, responsive interface.**

### Acceptance Criteria:
- [ ] Interface works on both desktop and mobile
- [ ] Basic dark mode support
- [ ] Keyboard shortcuts for common actions
- [ ] Character highlighting for differences between outputs


