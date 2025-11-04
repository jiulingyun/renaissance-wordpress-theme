// 3D地球可视化脚本
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('globe');
    if (!canvas) {
        console.warn('未找到ID为"globe"的canvas元素');
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    // 设置canvas大小 - 自适应容器宽度
    function resizeCanvas() {
        const container = canvas.parentElement;
        const containerWidth = container.clientWidth;
        const size = containerWidth;
        canvas.width = size;
        canvas.height = size;
        return size;
    }
    
    const size = resizeCanvas();
    
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = size * 0.37;
    
    let rotation = 0;
    let landPolygons = [];
    let points = [];
    let isDragging = false;
    let lastMouseX = 0;
    let rotationSpeed = -0.002; // 减慢旋转速度
    const initialRotationSpeed = -0.002; // 保存初始速度（减慢）
    let autoRotate = true;
    let animationTime = 0; // 用于动画计时
    let hoveredPoint = null; // 当前悬停的点
    let isHovering = false; // 是否正在悬停
    
    // 从GeoJSON加载真实的世界地图数据
    let worldGeoData = null;
    
    // 加载世界地图GeoJSON数据（使用轻量级数据源）
    async function loadWorldMap() {
        const sources = [
            'https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json',
            'https://cdn.jsdelivr.net/gh/johan/world.geo.json@master/countries.geo.json'
        ];
        
        for (let source of sources) {
            try {
                console.log('Trying to load:', source);
                const response = await fetch(source, { timeout: 5000 });
                if (!response.ok) continue;
                const data = await response.json();
                if (data && data.features && data.features.length > 0) {
                    console.log('✅ Successfully loaded map data! Total', data.features.length, 'countries');
                    return data;
                }
            } catch (error) {
                console.log('❌ This data source failed:', error.message);
            }
        }
        
        console.warn('⚠️ All online data sources failed, using local simplified data');
        return null;
    }
    
    // Check if point is on land (using GeoJSON data)
    function isLandFromGeoJSON(lat, lon, geoData) {
        if (!geoData || !geoData.features) {
            console.log('No GeoJSON data available');
            return false;
        }
        
        for (const feature of geoData.features) {
            if (!feature.geometry || !feature.geometry.coordinates) continue;
            
            if (feature.geometry.type === 'Polygon') {
                for (const ring of [feature.geometry.coordinates]) {
                    if (ring && ring[0] && isPointInPolygon(lon, lat, ring[0])) {
                        return true;
                    }
                }
            } else if (feature.geometry.type === 'MultiPolygon') {
                for (const polygon of feature.geometry.coordinates) {
                    if (!polygon) continue;
                    for (const ring of polygon) {
                        if (ring && isPointInPolygon(lon, lat, ring)) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
    
    // Backup simplified world land polygon data (lightweight)
    const worldLandData = {
        // Greenland (simplified)
        greenland: [
            [-73, 83], [-68, 83], [-63, 83], [-58, 82], [-53, 81], [-48, 80], 
            [-43, 79], [-40, 77], [-41, 75], [-43, 73], [-45, 71], [-48, 70], 
            [-50, 68], [-52, 66], [-54, 64], [-56, 62], [-54, 61], [-52, 60], 
            [-50, 60], [-48, 61], [-46, 62], [-44, 63], [-42, 64], [-40, 66], 
            [-38, 68], [-36, 70], [-34, 72], [-32, 74], [-30, 76], [-28, 77], 
            [-26, 78], [-24, 79], [-22, 80], [-20, 81], [-18, 82], [-16, 82], 
            [-16, 80], [-18, 78], [-20, 76], [-22, 74], [-24, 72], [-26, 70], 
            [-28, 68], [-30, 66], [-32, 64], [-34, 62], [-36, 61], [-38, 60], 
            [-40, 60], [-42, 61], [-44, 61], [-46, 60], [-48, 60], [-50, 61], 
            [-52, 62], [-54, 63], [-56, 64], [-58, 66], [-60, 68], [-62, 70], 
            [-64, 72], [-66, 74], [-68, 76], [-70, 78], [-72, 80], [-73, 82], [-73, 83]
        ],
        // North America - Canada and USA
        northAmerica: [
            [-168, 65], [-166, 67], [-164, 68], [-162, 69], [-160, 70], [-158, 71], 
            [-156, 71], [-154, 71], [-152, 70], [-150, 70], [-148, 69], [-146, 68], 
            [-144, 68], [-142, 69], [-140, 69], [-138, 69], [-136, 69], [-134, 68], 
            [-132, 67], [-130, 66], [-130, 64], [-131, 62], [-132, 60], [-133, 58], 
            [-134, 56], [-134, 54], [-133, 52], [-132, 50], [-131, 49], [-130, 49], 
            [-128, 49], [-126, 49], [-125, 49], [-124, 49], [-123, 48], [-123, 47], 
            [-122, 48], [-121, 48], [-120, 47], [-119, 46], [-118, 45], [-117, 44], 
            [-116, 43], [-115, 42], [-114, 41], [-114, 39], [-114, 37], [-114, 35], 
            [-114, 33], [-114, 32], [-115, 32], [-116, 31], [-117, 31], [-117, 29], 
            [-117, 27], [-116, 26], [-115, 25], [-113, 25], [-111, 25], [-109, 26], 
            [-108, 27], [-107, 28], [-106, 29], [-105, 29], [-104, 29], [-103, 29], 
            [-102, 28], [-101, 27], [-100, 26], [-99, 26], [-98, 26], [-97, 26], 
            [-96, 27], [-95, 28], [-95, 29], [-94, 29], [-93, 29], [-92, 29], 
            [-91, 29], [-90, 29], [-89, 29], [-88, 30], [-87, 30], [-86, 29], 
            [-85, 29], [-84, 29], [-83, 28], [-82, 27], [-81, 26], [-80, 25], 
            [-80, 24], [-81, 23], [-82, 22], [-83, 22], [-84, 22], [-85, 21], 
            [-86, 20], [-87, 19], [-88, 18], [-89, 17], [-90, 16], [-91, 15], 
            [-91, 14], [-90, 13], [-89, 13], [-88, 14], [-87, 14], [-86, 13], 
            [-85, 12], [-84, 11], [-83, 10], [-82, 9], [-81, 8], [-80, 8], 
            [-79, 8], [-78, 8], [-77, 9], [-76, 10], [-75, 10], [-74, 11], 
            [-73, 11], [-72, 11], [-71, 11], [-70, 11], [-69, 11], [-68, 11], 
            [-67, 11], [-66, 11], [-65, 12], [-65, 15], [-65, 18], [-65, 21], 
            [-66, 24], [-67, 27], [-67, 30], [-67, 33], [-67, 36], [-67, 39], 
            [-67, 42], [-67, 45], [-66, 46], [-65, 47], [-64, 47], [-63, 48], 
            [-62, 48], [-61, 48], [-60, 48], [-59, 49], [-58, 50], [-57, 51], 
            [-56, 52], [-57, 53], [-58, 54], [-59, 55], [-60, 56], [-61, 57], 
            [-62, 58], [-63, 59], [-64, 60], [-65, 61], [-66, 62], [-67, 63], 
            [-68, 64], [-69, 65], [-70, 66], [-71, 67], [-72, 68], [-73, 69], 
            [-74, 70], [-75, 71], [-76, 72], [-77, 73], [-78, 74], [-79, 75], 
            [-80, 76], [-81, 77], [-82, 77], [-84, 78], [-86, 78], [-88, 78], 
            [-90, 78], [-92, 79], [-94, 79], [-96, 79], [-98, 80], [-100, 80], 
            [-102, 80], [-104, 80], [-106, 79], [-108, 79], [-110, 79], [-112, 78], 
            [-114, 77], [-116, 76], [-118, 75], [-120, 74], [-122, 73], [-124, 72], 
            [-126, 71], [-128, 71], [-130, 70], [-132, 70], [-134, 70], [-136, 70], 
            [-138, 70], [-140, 70], [-142, 70], [-144, 70], [-146, 70], [-148, 70], 
            [-150, 70], [-152, 70], [-154, 70], [-156, 70], [-158, 69], [-160, 69], 
            [-162, 68], [-164, 67], [-166, 66], [-168, 65]
        ],
        // South America
        southAmerica: [
            [-81, 12], [-80, 11], [-79, 10], [-78, 9], [-77, 8], [-76, 7], 
            [-75, 5], [-74, 3], [-73, 1], [-73, -1], [-73, -3], [-72, -5], 
            [-71, -7], [-71, -9], [-70, -11], [-70, -13], [-70, -15], [-70, -17], 
            [-70, -19], [-70, -21], [-70, -23], [-70, -25], [-70, -27], [-70, -29], 
            [-70, -31], [-70, -33], [-70, -35], [-70, -37], [-70, -39], [-70, -41], 
            [-70, -43], [-70, -45], [-70, -47], [-70, -49], [-70, -51], [-70, -53], 
            [-69, -54], [-68, -55], [-67, -55], [-66, -55], [-65, -54], [-64, -53], 
            [-63, -52], [-62, -51], [-61, -50], [-60, -49], [-59, -47], [-58, -45], 
            [-57, -43], [-57, -41], [-56, -39], [-56, -37], [-55, -35], [-55, -33], 
            [-54, -31], [-54, -29], [-53, -27], [-52, -26], [-51, -25], [-50, -24], 
            [-49, -23], [-48, -22], [-48, -20], [-47, -19], [-46, -18], [-45, -17], 
            [-44, -16], [-43, -15], [-42, -14], [-41, -13], [-40, -12], [-39, -11], 
            [-38, -10], [-37, -9], [-36, -8], [-35, -7], [-35, -6], [-34, -5], 
            [-34, -3], [-34, -1], [-34, 1], [-34, 3], [-35, 4], [-36, 5], 
            [-37, 6], [-38, 7], [-39, 8], [-40, 8], [-42, 9], [-44, 9], 
            [-46, 10], [-48, 10], [-50, 11], [-52, 11], [-54, 11], [-56, 11], 
            [-58, 11], [-60, 10], [-62, 10], [-64, 9], [-66, 9], [-68, 8], 
            [-70, 8], [-72, 9], [-74, 10], [-76, 11], [-78, 11], [-80, 12], [-81, 12]
        ],
        // Europe
        europe: [
            [-10, 61], [-9, 59], [-8, 58], [-7, 56], [-6, 54], [-5, 52], 
            [-3, 51], [-1, 50], [1, 50], [2, 51], [3, 52], [4, 53], 
            [5, 54], [7, 54], [8, 55], [10, 55], [12, 56], [14, 56], 
            [16, 57], [18, 58], [20, 59], [22, 60], [23, 62], [24, 64], 
            [25, 66], [27, 68], [29, 69], [30, 70], [29, 70], [27, 70], 
            [25, 70], [23, 69], [21, 68], [19, 68], [17, 68], [15, 68], 
            [13, 68], [11, 68], [9, 68], [7, 67], [5, 66], [4, 64], 
            [4, 62], [5, 60], [6, 59], [7, 58], [9, 57], [10, 55], 
            [12, 54], [14, 52], [16, 51], [18, 49], [19, 48], [21, 47], 
            [22, 46], [23, 44], [23, 42], [22, 41], [21, 39], [20, 38], 
            [18, 37], [16, 37], [14, 38], [12, 39], [10, 40], [9, 42], 
            [8, 43], [6, 44], [5, 46], [4, 47], [2, 48], [0, 49], 
            [-2, 50], [-4, 50], [-5, 51], [-7, 53], [-8, 55], [-9, 57], 
            [-10, 59], [-10, 61]
        ],
        // Africa
        africa: [
            [-17, 28], [-16, 26], [-15, 24], [-14, 22], [-14, 20], [-13, 18], 
            [-12, 16], [-11, 14], [-10, 12], [-9, 10], [-7, 8], [-6, 7], 
            [-4, 6], [-2, 5], [0, 4], [2, 4], [4, 5], [6, 5], 
            [8, 6], [10, 7], [12, 8], [14, 9], [16, 10], [18, 11], 
            [20, 12], [22, 12], [24, 12], [26, 12], [28, 11], [30, 10], 
            [32, 9], [33, 8], [35, 6], [36, 5], [37, 3], [38, 1], 
            [39, -1], [40, -3], [41, -5], [42, -7], [43, -9], [43, -11], 
            [43, -13], [43, -15], [43, -17], [43, -19], [43, -21], [42, -23], 
            [41, -25], [40, -26], [39, -28], [38, -29], [37, -30], [35, -31], 
            [34, -32], [32, -33], [30, -34], [28, -33], [26, -32], [24, -31], 
            [22, -30], [21, -28], [19, -27], [18, -26], [16, -24], [15, -22], 
            [14, -20], [13, -19], [12, -17], [11, -15], [10, -13], [9, -12], 
            [8, -10], [7, -8], [6, -7], [5, -5], [4, -4], [3, -2], 
            [2, -1], [1, 1], [0, 2], [-1, 3], [-2, 5], [-3, 6], 
            [-4, 8], [-5, 9], [-6, 11], [-7, 12], [-8, 14], [-9, 15], 
            [-10, 17], [-11, 18], [-12, 20], [-13, 22], [-14, 24], [-15, 26], 
            [-16, 27], [-17, 28]
        ],
        // Asia
        asia: [
            [26, 41], [28, 40], [30, 39], [32, 38], [34, 38], [36, 38], 
            [38, 38], [40, 39], [42, 40], [44, 41], [46, 42], [48, 43], 
            [50, 44], [52, 45], [54, 46], [56, 46], [58, 47], [60, 47], 
            [62, 48], [64, 48], [66, 48], [68, 49], [70, 49], [72, 49], 
            [74, 49], [76, 49], [78, 50], [80, 50], [82, 50], [84, 50], 
            [86, 50], [88, 49], [90, 48], [92, 48], [94, 47], [96, 46], 
            [98, 45], [100, 45], [102, 44], [104, 43], [106, 43], [108, 42], 
            [110, 42], [112, 41], [114, 40], [116, 39], [118, 39], [120, 38], 
            [122, 38], [124, 38], [126, 39], [128, 40], [130, 41], [132, 42], 
            [134, 43], [136, 44], [138, 45], [140, 46], [142, 47], [144, 48], 
            [146, 50], [148, 51], [150, 53], [152, 54], [154, 56], [156, 57], 
            [158, 59], [160, 60], [162, 61], [164, 62], [166, 63], [168, 64], 
            [170, 65], [172, 66], [174, 67], [176, 68], [178, 69], [179, 70], 
            [178, 71], [176, 72], [174, 73], [172, 74], [170, 75], [168, 76], 
            [166, 77], [164, 77], [162, 78], [160, 78], [158, 78], [156, 78], 
            [154, 78], [152, 78], [150, 78], [148, 78], [146, 78], [144, 78], 
            [142, 77], [140, 77], [138, 77], [136, 76], [134, 76], [132, 76], 
            [130, 75], [128, 75], [126, 74], [124, 74], [122, 74], [120, 73], 
            [118, 73], [116, 72], [114, 72], [112, 71], [110, 71], [108, 70], 
            [106, 70], [104, 69], [102, 69], [100, 68], [98, 68], [96, 67], 
            [94, 67], [92, 66], [90, 66], [88, 65], [86, 65], [84, 64], 
            [82, 64], [80, 63], [78, 63], [76, 62], [74, 62], [72, 61], 
            [70, 61], [68, 60], [66, 60], [64, 59], [62, 59], [60, 58], 
            [58, 58], [56, 57], [54, 57], [52, 56], [50, 55], [48, 54], 
            [46, 53], [44, 52], [42, 51], [40, 50], [38, 48], [36, 47], 
            [34, 45], [32, 44], [30, 42], [28, 41], [26, 41]
        ],
        // Australia
        australia: [
            [113, -10], [114, -11], [116, -12], [117, -13], [119, -14], [120, -15], 
            [122, -16], [124, -17], [126, -18], [127, -19], [129, -20], [130, -21], 
            [132, -22], [133, -23], [135, -24], [136, -26], [137, -27], [138, -29], 
            [139, -30], [140, -32], [141, -33], [142, -35], [143, -36], [144, -38], 
            [145, -39], [146, -40], [147, -41], [148, -42], [149, -42], [150, -43], 
            [151, -43], [152, -43], [153, -43], [153, -42], [153, -41], [152, -40], 
            [152, -39], [151, -38], [151, -37], [150, -36], [150, -35], [149, -34], 
            [149, -33], [148, -32], [148, -31], [147, -30], [146, -29], [145, -28], 
            [144, -27], [143, -26], [142, -25], [141, -24], [140, -23], [139, -22], 
            [138, -21], [137, -20], [135, -19], [134, -19], [132, -18], [131, -17], 
            [129, -17], [128, -16], [126, -16], [125, -15], [123, -15], [122, -14], 
            [120, -14], [119, -13], [117, -13], [116, -12], [114, -11], [113, -10]
        ],
        // Indian Peninsula (improved)
        india: [
            [68, 36], [69, 35], [70, 34], [72, 32], [74, 30], [76, 28], 
            [77, 27], [77, 26], [78, 25], [79, 25], [80, 26], [82, 27], 
            [84, 28], [86, 28], [88, 28], [90, 27], [92, 26], [93, 24], 
            [93, 22], [92, 21], [91, 19], [89, 17], [88, 15], [87, 13], 
            [86, 11], [85, 10], [84, 9], [83, 8], [81, 8], [79, 8], 
            [77, 8], [76, 9], [75, 11], [74, 13], [73, 16], [72, 18], 
            [71, 21], [70, 24], [69, 27], [68, 30], [68, 33], [68, 36]
        ],
        // Southeast Asian Peninsula (simplified)
        indochina: [
            [92, 21], [95, 24], [99, 27], [103, 24], [107, 19], [109, 13], 
            [106, 9], [103, 7], [100, 8], [96, 12], [92, 16], [92, 21]
        ],
        // Mainland China (improved version)
        china: [
            // Northeast
            [135, 48], [133, 47], [131, 46], [129, 45], [127, 44], [125, 43], 
            [123, 42], [121, 41], [119, 40], [117, 40],
            // North China coastal
            [116, 39], [115, 38], [117, 37], [118, 36], [119, 35], [120, 34], 
            [121, 33], [121, 32], [121, 31], [120, 30], [119, 29], [118, 28],
            [117, 27], [116, 26], [115, 25], [114, 24], [113, 23],
            // South China
            [112, 22], [111, 22], [110, 21], [109, 21], [108, 21], [107, 22],
            [106, 23], [105, 24], [104, 25], [103, 26], [102, 27], [101, 28],
            // Southwest
            [100, 28], [99, 28], [98, 28], [97, 28], [96, 28], [95, 28],
            [94, 28], [93, 28], [92, 28], [91, 28], [90, 28], [89, 29],
            [88, 30], [87, 31], [86, 32], [85, 33], [84, 34], [83, 35],
            // West
            [82, 36], [81, 37], [80, 38], [79, 39], [78, 39], [77, 39],
            [76, 39], [75, 39], [74, 39], [73, 39],
            // Northwest
            [73, 40], [74, 41], [75, 42], [77, 42], [79, 42], [81, 42],
            [83, 42], [85, 42], [87, 42], [89, 42], [91, 42], [93, 42],
            [95, 42], [97, 42], [99, 42], [101, 42], [103, 42], [105, 42],
            // North back to Northeast
            [107, 42], [109, 42], [111, 42], [113, 42], [115, 42], [117, 42],
            [119, 42], [121, 42], [123, 43], [125, 44], [127, 45], [129, 46],
            [131, 47], [133, 48], [135, 48]
        ],
        // Korean Peninsula
        korea: [
            [124, 43], [125, 42], [126, 41], [127, 40], [128, 39], [129, 38], 
            [129, 37], [129, 36], [128, 35], [127, 35], [126, 35], [125, 36], 
            [125, 37], [125, 38], [125, 39], [125, 40], [125, 41], [124, 42], 
            [124, 43]
        ],
        // Taiwan
        taiwan: [
            [120, 25], [121, 25], [121.5, 24], [122, 23], [121.5, 22], 
            [121, 22], [120.5, 22], [120, 22.5], [120, 24], [120, 25]
        ],
        // Hainan Island
        hainan: [
            [108, 20], [109, 20], [110, 19.5], [110, 18.5], [109, 18], 
            [108, 18], [108, 19], [108, 20]
        ],
        // Japanese Archipelago (corrected position: mainly 135°E-145°E)
        japan: [
            // Kyushu (southern part)
            [130, 33], [131, 32], [132, 31], [130.5, 31], [130, 32], [130, 33],
            // Western Honshu
            [133, 34], [134, 35], [135, 35.5], [136, 36], [137, 36.5],
            // Central Honshu (Tokyo area)
            [138, 37], [139, 37.5], [140, 38], [140.5, 38.5],
            // Northeast Honshu
            [141, 39], [141.5, 40], [142, 41], [142.5, 42], [143, 43],
            // Hokkaido
            [143.5, 44], [144, 44.5], [145, 45], [145.5, 45.5],
            // Return southward
            [145, 45], [144.5, 44], [144, 43], [143, 42], [142, 41],
            [141, 40], [140, 39], [139, 38], [138, 37], [137, 36],
            [136, 35.5], [135, 35], [134, 34.5], [133, 34], [132, 33.5],
            [131, 33], [130, 33]
        ],
        // Philippines
        philippines: [
            [120, 18], [121, 18], [122, 17], [123, 16], [124, 15], [125, 14],
            [126, 13], [126, 12], [125, 11], [124, 10], [123, 9], [122, 8],
            [121, 7], [120, 7], [119, 8], [119, 9], [119, 11], [119, 13],
            [119, 15], [119, 17], [120, 18]
        ],
        // Southeast Asian Islands (Indonesia, etc.)
        seAsia: [
            [95, 6], [100, 8], [105, 6], [110, 2], [115, -2], [120, -3], 
            [125, -5], [130, -3], [135, -5], [140, -5], [145, -8], [150, -10],
            [148, -9], [145, -7], [140, -4], [135, -3], [130, -1], [125, -3],
            [120, -1], [115, 0], [110, 1], [105, -5], [100, 0], [95, 6]
        ],
        // New Zealand
        newZealand: [
            [166, -34], [168, -36], [170, -38], [172, -40], [174, -41], [176, -41],
            [178, -40], [178, -38], [176, -36], [174, -35], [172, -34], [170, -34],
            [168, -34], [166, -34]
        ],
        // Sri Lanka
        sriLanka: [
            [79.5, 10], [80, 9], [81, 8], [81, 7], [80.5, 6.5], [80, 6.5], 
            [79.5, 7], [79.5, 8.5], [79.5, 10]
        ]
    };
    
    // Point-in-polygon algorithm (Ray Casting)
    function isPointInPolygon(lon, lat, polygon) {
        let inside = false;
        for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
            const xi = polygon[i][0], yi = polygon[i][1];
            const xj = polygon[j][0], yj = polygon[j][1];
            
            const intersect = ((yi > lat) !== (yj > lat))
                && (lon < (xj - xi) * (lat - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
        return inside;
    }
    
    // Determine if point is on land
    function isLand(lat, lon) {
        // Normalize longitude
        while (lon > 180) lon -= 360;
        while (lon < -180) lon += 360;
        
        // If GeoJSON data is available, use it first
        if (worldGeoData) {
            return isLandFromGeoJSON(lat, lon, worldGeoData);
        }
        
        // Otherwise use backup data
        for (let continent in worldLandData) {
            if (isPointInPolygon(lon, lat, worldLandData[continent])) {
                return true;
            }
        }
        return false;
    }
    
    // Show loading message
    function showLoadingMessage(message) {
        // Clear canvas, make background transparent
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#fff';
        ctx.font = '20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(message, centerX, centerY);
    }
    
    // 生成地球上的点
    async function generatePoints() {
        showLoadingMessage('Loading world map data...');
        console.log('Loading world map data...');
        
        worldGeoData = await loadWorldMap();
        
        if (worldGeoData && worldGeoData.features && worldGeoData.features.length > 0) {
            console.log('Successfully loaded GeoJSON data, containing', worldGeoData.features.length, 'features');
        } else {
            console.log('Using backup map data');
            worldGeoData = null; // Ensure using backup data
        }
        
        showLoadingMessage('Generating Earth land points...');
        console.log('Generating Earth land points...');
        const numPoints = 18000; // Increase points for better accuracy
        
        for (let i = 0; i < numPoints; i++) {
            const phi = Math.acos(1 - 2 * (i + 0.5) / numPoints);
            const theta = Math.PI * (1 + Math.sqrt(5)) * (i + 0.5);
            
            const lat = 90 - phi * 180 / Math.PI;
            const lon = ((theta * 180 / Math.PI) % 360) - 180;
            
            if (isLand(lat, lon)) {
                points.push({
                    lat: lat,
                    lon: lon,
                    phi: phi,
                    theta: theta,
                    highlight: false,
                    animationOffset: 0,
                    info: null
                });
            }
        }
        
        console.log('Generated', points.length, 'land points');
        
        // Add blue dot markers for real city locations
        const cities = [
            { name: 'New York', region: 'USA · New York', logo: '🗽', lat: 40.7128, lon: -74.0060 },
            { name: 'London', region: 'UK · England', logo: '🏰', lat: 51.5074, lon: -0.1278 },
            { name: 'Tokyo', region: 'Japan · Kanto', logo: '🗼', lat: 35.6762, lon: 139.6503 },
            { name: 'Shanghai', region: 'China · East China', logo: '🏙️', lat: 31.2304, lon: 121.4737 },
            { name: 'Paris', region: 'France · Île-de-France', logo: '🗼', lat: 48.8566, lon: 2.3522 },
            { name: 'Sydney', region: 'Australia · New South Wales', logo: '🏖️', lat: -33.8688, lon: 151.2093 },
            { name: 'Dubai', region: 'UAE · Dubai Emirate', logo: '🏛️', lat: 25.2048, lon: 55.2708 },
            { name: 'Singapore', region: 'Singapore · Central', logo: '🦁', lat: 1.3521, lon: 103.8198 },
            { name: 'Hong Kong', region: 'China · Hong Kong SAR', logo: '🌃', lat: 22.3193, lon: 114.1694 },
            { name: 'Seoul', region: 'South Korea · Seoul', logo: '🏯', lat: 37.5665, lon: 126.9780 },
            { name: 'Moscow', region: 'Russia · Moscow Oblast', logo: '🏛️', lat: 55.7558, lon: 37.6173 },
            { name: 'Berlin', region: 'Germany · Berlin', logo: '🏛️', lat: 52.5200, lon: 13.4050 },
            { name: 'Toronto', region: 'Canada · Ontario', logo: '🍁', lat: 43.6532, lon: -79.3832 },
            { name: 'Mumbai', region: 'India · Maharashtra', logo: '🕌', lat: 19.0760, lon: 72.8777 },
            { name: 'São Paulo', region: 'Brazil · São Paulo', logo: '⚽', lat: -23.5505, lon: -46.6333 },
            { name: 'Los Angeles', region: 'USA · California', logo: '🎬', lat: 34.0522, lon: -118.2437 },
            { name: 'Chicago', region: 'USA · Illinois', logo: '🏙️', lat: 41.8781, lon: -87.6298 },
            { name: 'Beijing', region: 'China · North China', logo: '🏯', lat: 39.9042, lon: 116.4074 },
            { name: 'Shenzhen', region: 'China · South China', logo: '🏙️', lat: 22.5431, lon: 114.0579 },
            { name: 'Melbourne', region: 'Australia · Victoria', logo: '🏙️', lat: -37.8136, lon: 144.9631 }
        ];
        
        cities.forEach(city => {
            // Convert latitude and longitude to spherical coordinates
            const phi = (90 - city.lat) * Math.PI / 180;
            const theta = (city.lon + 180) * Math.PI / 180;
            
            points.push({
                lat: city.lat,
                lon: city.lon,
                phi: phi,
                theta: theta,
                highlight: true,
                animationOffset: Math.random() * 2000,
                info: city
            });
        });
        
        console.log('Added', cities.length, 'city marker points');
        
        // Start drawing
        draw();
    }
    
    // 3D coordinate transformation
    function sphericalTo3D(phi, theta, r, rotationY) {
        let x = r * Math.sin(phi) * Math.cos(theta);
        let y = r * Math.cos(phi);
        let z = r * Math.sin(phi) * Math.sin(theta);
        
        const cosRot = Math.cos(rotationY);
        const sinRot = Math.sin(rotationY);
        const xRotated = x * cosRot - z * sinRot;
        const zRotated = x * sinRot + z * cosRot;
        
        return {x: xRotated, y: y, z: zRotated};
    }
    
    function project3D(x, y, z) {
        const perspective = 800;
        const scale = perspective / (perspective - z);
        return {
            x: centerX + x * scale,
            y: centerY - y * scale,
            z: z,
            scale: scale
        };
    }
    
    // Draw tooltip
    function drawTooltip(point) {
        const padding = 15;
        const logoSize = 40;
        const boxWidth = 200;
        const boxHeight = 100;
        
        // Calculate tooltip position (above the point)
        let tooltipX = point.x - boxWidth / 2;
        let tooltipY = point.y - boxHeight - 30;
        
        // Boundary check
        if (tooltipX < 10) tooltipX = 10;
        if (tooltipX + boxWidth > canvas.width - 10) tooltipX = canvas.width - boxWidth - 10;
        if (tooltipY < 10) tooltipY = point.y + 30; // If not enough space above, show below
        
        // 绘制阴影
        ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
        ctx.shadowBlur = 20;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 5;
        
        // 绘制背景
        ctx.fillStyle = 'rgba(20, 20, 30, 0.95)';
        ctx.beginPath();
        ctx.roundRect(tooltipX, tooltipY, boxWidth, boxHeight, 10);
        ctx.fill();
        
        // 重置阴影
        ctx.shadowColor = 'transparent';
        ctx.shadowBlur = 0;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;
        
        // 绘制边框
        ctx.strokeStyle = 'rgba(74, 158, 255, 0.5)';
        ctx.lineWidth = 2;
        ctx.stroke();
        
        // 绘制连接线
        ctx.beginPath();
        ctx.moveTo(point.x, point.y);
        ctx.lineTo(tooltipX + boxWidth / 2, tooltipY + boxHeight);
        ctx.strokeStyle = 'rgba(74, 158, 255, 0.3)';
        ctx.lineWidth = 1;
        ctx.stroke();
        
        // 绘制LOGO（表情符号）
        ctx.font = `${logoSize}px Arial`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(point.info.logo, tooltipX + boxWidth / 2, tooltipY + 30);
        
        // 绘制名称
        ctx.font = 'bold 18px Arial';
        ctx.fillStyle = '#fff';
        ctx.textAlign = 'center';
        ctx.fillText(point.info.name, tooltipX + boxWidth / 2, tooltipY + 65);
        
        // 绘制地区
        ctx.font = '12px Arial';
        ctx.fillStyle = 'rgba(200, 200, 200, 0.8)';
        ctx.fillText(point.info.region, tooltipX + boxWidth / 2, tooltipY + 85);
    }
    
    // 绘制函数
    function draw() {
        // 清除画布，使背景透明
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // 绘制球体背景
        const gradient = ctx.createRadialGradient(
            centerX - radius * 0.5, centerY - radius * 0.5, radius * 0.05,
            centerX, centerY, radius
        );
        gradient.addColorStop(0, 'rgba(15, 15, 25, 0.98)');
        gradient.addColorStop(0.05, 'rgba(8, 8, 15, 0.99)');
        gradient.addColorStop(0.2, 'rgba(2, 2, 5, 1)');
        gradient.addColorStop(0.45, 'rgba(0, 0, 0, 1)');
        gradient.addColorStop(1, 'rgba(0, 0, 0, 1)');
        
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
        ctx.fillStyle = gradient;
        ctx.fill();
        
        // 内阴影
        const innerShadow = ctx.createRadialGradient(
            centerX + radius * 0.1, centerY + radius * 0.1, radius * 0.1,
            centerX, centerY, radius
        );
        innerShadow.addColorStop(0, 'transparent');
        innerShadow.addColorStop(0.15, 'rgba(0, 0, 0, 0.3)');
        innerShadow.addColorStop(0.3, 'rgba(0, 0, 0, 0.7)');
        innerShadow.addColorStop(0.5, 'rgba(0, 0, 0, 0.9)');
        innerShadow.addColorStop(1, 'rgba(0, 0, 0, 1)');
        
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
        ctx.fillStyle = innerShadow;
        ctx.fill();
        
        // 排序并绘制点
        const sortedPoints = points.map(p => {
            const coords3D = sphericalTo3D(p.phi, p.theta, radius, rotation);
            const projected = project3D(coords3D.x, coords3D.y, coords3D.z);
            return {...p, ...projected};
        }).sort((a, b) => a.z - b.z);
        
        sortedPoints.forEach(point => {
            if (point.z > 0) {
                const opacity = Math.max(0.2, point.z / radius);
                const baseSize = point.highlight ? 2.5 : 1.6; // 蓝点稍大，白点更小
                const size = Math.max(0.5, baseSize + point.scale * 0.1); // 减小点的大小
                
                if (point.highlight) {
                    // 计算动画进度（2秒循环）
                    const animationDuration = 2000; // 2秒
                    const currentTime = (animationTime + point.animationOffset) % animationDuration;
                    const progress = currentTime / animationDuration; // 0 到 1
                    
                    // 绘制普通的蓝点（不动画）
                    ctx.beginPath();
                    ctx.arc(point.x, point.y, size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(74, 158, 255, ${opacity * 0.9})`;
                    ctx.fill();
                    
                    // 绘制动画波纹（放大 + 淡出）
                    const waveSize = size + progress * 12; // 从原始大小放大到 +12
                    const waveOpacity = opacity * 0.8 * (1 - progress); // 从0.8逐渐到0
                    
                    if (waveOpacity > 0.05) {
                        ctx.beginPath();
                        ctx.arc(point.x, point.y, waveSize, 0, Math.PI * 2);
                        ctx.strokeStyle = `rgba(74, 158, 255, ${waveOpacity})`;
                        ctx.lineWidth = 1.5;
                        ctx.stroke();
                        
                        // 添加发光效果
                        ctx.shadowBlur = 12;
                        ctx.shadowColor = `rgba(74, 158, 255, ${waveOpacity * 0.5})`;
                        ctx.stroke();
                        ctx.shadowBlur = 0;
                    }
                } else {
                    // 普通白点 - 更白更小
                    ctx.beginPath();
                    ctx.arc(point.x, point.y, size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 255, 255, ${opacity * 0.8})`;
                    ctx.fill();
                }
            }
        });
        
        // 绘制悬停弹窗 - 已隐藏
        // if (hoveredPoint && hoveredPoint.info) {
        //     drawTooltip(hoveredPoint);
        // }
        
        // 自动旋转或根据拖拽速度旋转（悬停时不旋转）
        if (autoRotate && !isHovering) {
            rotation += rotationSpeed;
        }
        
        // 更新动画时间（用于蓝点动画）
        animationTime += 16; // 假设约60fps，每帧约16ms
        
        requestAnimationFrame(draw);
    }
    
    // 检查鼠标是否悬停在蓝点上
    function checkHover(mouseX, mouseY) {
        hoveredPoint = null;
        isHovering = false;
        
        // 计算所有点的当前位置
        const sortedPoints = points.map(p => {
            const coords3D = sphericalTo3D(p.phi, p.theta, radius, rotation);
            const projected = project3D(coords3D.x, coords3D.y, coords3D.z);
            return {...p, ...projected};
        }).filter(p => p.z > 0); // 只检查前面的点
        
        // 检查鼠标是否在某个蓝点上
        for (let i = sortedPoints.length - 1; i >= 0; i--) {
            const point = sortedPoints[i];
            if (point.highlight && point.info) {
                const distance = Math.sqrt(
                    Math.pow(mouseX - point.x, 2) + 
                    Math.pow(mouseY - point.y, 2)
                );
                const hitRadius = 10; // 扩大点击区域
                
                if (distance < hitRadius) {
                    hoveredPoint = point;
                    isHovering = true;
                    canvas.style.cursor = 'pointer';
                    return;
                }
            }
        }
        
        // 如果没有悬停在蓝点上
        if (!isDragging) {
            canvas.style.cursor = 'grab';
        }
    }
    
    // 鼠标事件处理
    canvas.addEventListener('mousedown', (e) => {
        isDragging = true;
        lastMouseX = e.clientX;
        autoRotate = false;
        canvas.style.cursor = 'grabbing';
    });
    
    canvas.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        
        if (isDragging) {
            const deltaX = e.clientX - lastMouseX;
            rotation -= deltaX * 0.01; // 反转方向
            lastMouseX = e.clientX;
            // 拖动时不检查悬停
            isHovering = false;
            hoveredPoint = null;
        } else {
            // 不拖动时检查是否悬停在蓝点上 - 已禁用
            // checkHover(mouseX, mouseY);
        }
    });
    
    canvas.addEventListener('mouseup', () => {
        isDragging = false;
        canvas.style.cursor = 'grab';
        // 恢复初始速度
        rotationSpeed = initialRotationSpeed;
        autoRotate = true;
    });
    
    canvas.addEventListener('mouseleave', () => {
        if (isDragging) {
            isDragging = false;
            canvas.style.cursor = 'grab';
            // 恢复初始速度
            rotationSpeed = initialRotationSpeed;
            autoRotate = true;
        }
    });
    
    // 触摸事件支持
    let lastTouchX = 0;
    
    canvas.addEventListener('touchstart', (e) => {
        isDragging = true;
        lastTouchX = e.touches[0].clientX;
        autoRotate = false;
        e.preventDefault();
    });
    
    canvas.addEventListener('touchmove', (e) => {
        if (isDragging) {
            const deltaX = e.touches[0].clientX - lastTouchX;
            rotation -= deltaX * 0.01; // 反转方向
            lastTouchX = e.touches[0].clientX;
            e.preventDefault();
        }
    });
    
    canvas.addEventListener('touchend', () => {
        isDragging = false;
        // 恢复初始速度
        rotationSpeed = initialRotationSpeed;
        autoRotate = true;
    });
    
    // 设置鼠标样式
    canvas.style.cursor = 'grab';
    
    // 启动：加载数据并生成点
    generatePoints();
    
    // 窗口大小调整事件
    window.addEventListener('resize', () => {
        resizeCanvas();
    });
});
